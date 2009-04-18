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

    /**
     * Compress output HTML
     *
     * @var boolean
     */
    protected $_compressHtml = false;

    /**
     * Compress output CSS
     *
     * @var string
     */
    protected $_compressCss = false;

    /**
     * Compress output JS
     *
     * @var boolean
     */
    protected $_compressJs = false;
    

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

    /**
     * Enable HTML compression
     *
     * @param boolean $value
     */
    public function setCompressHtml($value)
    {
        $this->_compressHtml = $value;
    }

    /**
     * Get HTML compression status
     *
     * @return boolean
     */
    public function getCompressHtml()
    {
        return $this->_compressHtml;
    }

    /**
     * Enable CSS compression
     *
     * @param boolean $value
     */
    public function setCompressCss($value)
    {
        $this->_compressCss = $value;
    }

    /**
     * Get CSS compression status
     *
     * @return boolean
     */
    public function getCompressCss()
    {
        return $this->_compressCss;
    }

    /**
     * Enable in-page JavaScript compression
     *
     * @param boolean $value
     */
    public function setCompressJs($value)
    {
        $this->_compressJs = $value;
    }

    /**
     * Get JavaScript compression status
     *
     * @return boolean
     */
    public function getCompressJs()
    {
        return $this->_compressJs;
    }
}