<?php
/**
 * Check if the email is already exists in the database
 */

require_once 'Zend/Validate/Abstract.php';

/**
* @desc Валидатор для проверки, зарегистрирован ли Email
*/
class App_Validate_UniqueField extends Zend_Validate_Abstract {

	const IS_EXISTS = 'exists';

    /**
     * Value checked
     *
     * @var string
     */
	protected $_value;

	/**
	 * @var array
	 */
	 protected $_messageTemplates = array(
		self::IS_EXISTS  => 'The same value is already exists in the database.'
	);

	/**
	 * @var array
	 */
	 protected $_messageVariables = array(
	 	'value' => '_value'
	 );

   /**
    * Email, который не нужно учитывать
    *
    * @var string
    */
   protected $_skip;

   /**
    * Model name
    *
    * @var string
    */
   protected $_model;

   /**
    * Field name
    *
    * @var string
    */
   protected $_field;

   /**
    * Sets validator options
    *
    * @param string $module - Module name
    * @param string $field - Field name
	* @return void
	*/
	public function __construct($model, $field, $skip = null)
    {
        if (!$model || !$field) {
            return null;
        }
        
        $this->_model = $model;
        $this->_field = $field;
        $this->_skip = $skip;
    }


   /**
    * Defined by Zend_Validate_Interface
	*
	* @param  string $value
	* @return boolean
	*/
	public function isValid($value) {

        if ($value == $this->_skip) {
            return true;
        }

		$this->_value = $value;

		$model = new $this->_model();
        $select = $model->select()
                        ->where('`'.$this->_field.'` = ?', trim($value));

		$record = $model->fetchAll($select);

		if (count($record) > 0) {
			// the field with such value is already exists
			$this->_error(self::IS_EXISTS);
			return false;
		} else {
			// such value is not exists yet
			return true;
		}
	}

}