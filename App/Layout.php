<?php
/**
 * Provide Layout support for MVC applications
 */
class App_Layout extends Zend_Layout {

    /**
     * Plugin class
     * @var string
     */
    protected $_pluginClass = 'App_Layout_Controller_Plugin_Layout';

    /*public function __construct($options = null, $initMvc = false)
    {
//        $this->_pluginClass = 'App_Layout_Controller_Plugin_Layout';
        echo 'test';exit;
        parent::__construct($options, $initMvc);
    }*/

    /**
     * Static method for initialization with MVC support
     *
     * @param  string|array|Zend_Config $options
     * @return Zend_Layout
     */
    public static function startMvc($options = null)
    {
        if (null === self::$_mvcInstance) {
            self::$_mvcInstance = new self($options, true);
        } elseif (is_string($options)) {
            self::$_mvcInstance->setLayoutPath($options);
        } else {
            self::$_mvcInstance->setOptions($options);
        }

        return self::$_mvcInstance;
    }
}