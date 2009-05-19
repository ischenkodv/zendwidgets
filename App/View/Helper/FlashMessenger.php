<?php
/**
 * FlashMessenger View Helper
 */
class App_View_Helper_FlashMessenger extends Zend_View_Helper_Placeholder_Container_Standalone
{

    /**
     * @var string registry key
     */
	protected $_regKey = 'App_View_Helpers_FlashMessenger';

    /**
     * List of messages
     *
     * @var array
     */
	protected $_messages = array();

    protected $_namespaces = array(
        'success',
        'error',
        'notice',
        'warning'
    );

    /**
     * Prefix that is prepended before every class name of the message block
     *
     * @var string
     */
    protected $_classPrefix = 'flash';

    /**
     * Instance of FlashMessenger
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;

    public function __construct()
    {
        parent::__construct();

        // create instance of FlashMessenger
        $this->_flashMessenger = new Zend_Controller_Action_Helper_FlashMessenger();

        // loop through namespaces and collect messages
        foreach ($this->_namespaces as $namespace) {
            $this->_flashMessenger->setNamespace($namespace);
            $this->_messages[$namespace] = $this->_flashMessenger->getMessages();
        }
    }

    public function flashMessenger()
    {
    	return $this;
    }

    /**
     * Render messages
     *
     * @param string|int $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $indent = (null !== $indent)
                ? $this->getWhitespace($indent)
                : $this->getIndent();

        $xhtml = $this->getPrefix();

        if (count($this->_messages)) {
            foreach ($this->_messages as $namespace=>$messages) {
                if (count($messages)) {
                    $xhtml .= '<ul class="'.$this->_classPrefix.ucfirst($namespace).'">';

                    foreach ($messages as $m) {
                        $xhtml .= '<li>'.$m.'</li>';
                    }

                    $xhtml .= '</ul>';
                }
            }
        }

        $xhtml .= $this->getPostfix();

        return $indent.$xhtml;
    }

}