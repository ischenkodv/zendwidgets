<?php
/**
 * FlashMessenger View Helper
 */
class App_View_Helper_FlashMessenger extends Zend_View_Helper_Placeholder_Container_Standalone
{
    /**
     * View namespace.
     * 
     * @var type string
     */
    protected $_namespace;

    /**
     * Prefix that is prepended before every class name of the message block
     *
     * @var string
     */
    protected $_classPrefix = 'flash';

    /**
     * Instance of FlashMessenger
     *
     * @var App_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;

    public function __construct()
    {
        parent::__construct();
        
        $this->_flashMessenger = new App_Controller_Action_Helper_FlashMessenger();
    }

    public function flashMessenger($namespace = null)
    {
        $this->setNamespace($namespace !== null ? $namespace : 'default');
        $this->_namespace = $namespace;
        
    	return $this;
    }
    
    /**
     * Set namespace for flash messenger.
     * 
     * @param string $namespace 
     */
    public function setNamespace($namespace)
    {
        $this->_flashMessenger->setNamespace($namespace);
    }
    

    /**
     * Add message in flash messenger.
     * 
     * @param string $msg
     */
	public function addMessage($msg)
	{
        $this->_flashMessenger->addMessage($msg);
	}

    /**
     * Render messages.
     *
     * @param string|int $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $str = $this->getPrefix();
        
        if ($this->_namespace) {
            $namespaces = array($this->_namespace);
        } else {
            $namespaces = $this->_flashMessenger->getAvailableNamespaces();
        }

        foreach ($namespaces as $namespace) {
            $str .= $this->render($namespace);
        }

        $str .= $this->getPostfix();

        return $indent.$str;
    }
    
    /**
     * Render particular namespace.
     * 
     * @param type $namespace
     * @return string 
     */
    public function render($namespace)
    {
        $str = '';
        
        $this->_flashMessenger->setNamespace($namespace);
        $messages = $this->_flashMessenger->getMessages();

        if (count($messages) > 0) {

            $str .= '<ul class="'.$this->_classPrefix.ucfirst($namespace).'">';

            foreach ($messages as $message) {
                $str .= '<li>'.$message.'</li>';
            }

            $str .= '</ul>';
        }
        
        return $str;
    }

}