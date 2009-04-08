<?php
/* 
 * Base controller for application
 */
class App_Controller_Base extends Zend_Controller_Action {


    /**
     * Show application error page
     * @param Zend_Exception | string $exception
     */
    protected function fatalError($exception)
    {
        if ($exception instanceof Zend_Exception) {
            $message = $exception->getMessage();
        }
        else {
            $message = $exception;
        }

        $this->_forward('fatal', 'error', 'default', array('message' => $message));
    }

}
