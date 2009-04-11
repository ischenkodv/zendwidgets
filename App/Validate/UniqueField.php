<?php
/**
 * Check if the email is already exists in the database
 */

require_once 'Zend/Validate/Abstract.php';

/**
* @desc Валидатор для проверки, зарегистрирован ли Email
*/
class App_Validate_UniqueField extends Zend_Validate_Abstract {

	const EMAIL_EXISTS = 'exists';

    /**
     * Email checked
     *
     * @var string
     */
	protected $_email;

	/**
	 * @var array
	 */
	 protected $_messageTemplates = array(
		self::EMAIL_EXISTS  => 'This email is already registered in the system.'
	);

	/**
	 * @var array
	 */
	 protected $_messageVariables = array(
	 	'email' => '_email'
	 );

   /**
    * Email, который не нужно учитывать
    *
    * @var string
    */
   protected $_skip;

   protected $_model;

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
	*
	* @return boolean
	*/
	public function isValid($value) {

        if ($value == $this->_skip) {
            return true;
        }

		$this->_email = $value;

		$model = new $this->_model();
        $select = $model->select()
                        ->where('`email` = ?', trim($value));

		$record = $model->fetchAll($select);

		if (count($record) > 0) {
			// такой email уже существует
			$this->_error(self::EMAIL_EXISTS);
			return false;
		} else {
			// такой email еще не регистрировался
			return true;
		}
	}

}