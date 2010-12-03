<?php
class App_Controller_Action_Helper_FlashMessenger
    extends Zend_Controller_Action_Helper_FlashMessenger {
    
    public function getAvailableNamespaces()
    {
        $namespaces = array();
        
        if (count(self::$_messages) > 0) {
            foreach (self::$_messages as $namespace=>$values) {
                $namespaces[] = $namespace;
            }
        }
        
        return $namespaces;
    }
}