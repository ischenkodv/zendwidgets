<?php
/* 
 * Base controller for application
 */
class App_Controller_Base extends Zend_Controller_Action {

    /**
     * Data of the current user
     *
     * @var array
     */
    protected $_currentUser;

    /**
     * Instance of flash messenger
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;

    /**
     * Instance of Zend_Acl
     *
     * @var Zend_Acl
     */
    protected $_acl;

    public function init()
    {
        parent::init();

        // create instance of flash messenger
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');

        // get current user
        $usersModel = new Users();
        $this->view->currentUser = $this->_currentUser = $usersModel->getCurrentUser();

        // init user roles
        $this->_initRoles();

        // stripslashes all data in the views
        $this->view->setEscape(array('App_Globals','escape'));
    }

    /**
     * Добавляет сообщения об успешном выполнении в стек сообщений flash
     * @param Array|String $msg
     */
	public function msgSuccess($msg)
	{
        $this->_flashMessenger->setNamespace('success');
        $this->_flashMessenger->addMessage($msg);
	}

    /**
     * Добавляет сообщение об ошибке в стек сообщений flash
     * @param Array|String $msg
     * @return void
     */
	public function msgError($msg)
	{
        $this->flashMessenger->setNamespace('error');
        $this->flashMessenger->addMessage($msg);
	}

    /**
     * Добавляет предупреждение в стек сообщений flash
     * @param Array|String $msg
     * @return void
     */
	public function msgWarning($msg)
	{
        $this->flashMessenger->setNamespace('warning');
        $this->flashMessenger->addMessage($msg);
	}

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

    /**
     * Инициализация ролей пользователя
     */
    private function _initRoles()
    {
        if (!$this->_acl instanceof Zend_Acl) {
            $this->_acl = new Zend_Acl();
        }

        // init all roles
        $rolesModel = new Roles();
        $roles = $rolesModel->fetchAll();
        foreach ($roles as $r) {
            $this->_acl->addRole(new Zend_Acl_Role($r->name));
        }
    }

}
