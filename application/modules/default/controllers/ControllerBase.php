<?php

class modules_default_controllers_ControllerBase extends controllers_ControllerBase {

    /**
     * Set content layout (full, short)
     * @var string
     */
    public $_contentLayout = 'full';
    protected $_bForLoggedUsersOnly = false;

    public function init() {
        $this->_initAcl();
        parent::init();

        if (!App_User::isLogged() && $this->_bForLoggedUsersOnly) {
            $this->_redirect('/login/');
        }


        $oZendSession = Zend_Registry::getInstance()->get('Zend_Session_Namespace');

        $this->view->headLink()->headLink(array('rel' => 'favicon',
            'href' => '/favicon.ico'), 'PREPEND');
        $this->view->headLink()->appendStylesheet('/styles/styles.css?r='.  rand(1, 10000));
        $this->view->headLink()->appendStylesheet('/bootstrap/css/bootstrap.min.css');
        $this->view->headLink()->appendStylesheet('/bootstrap/css/bootstrap-responsive.min.css');
        $this->view->headLink()->appendStylesheet('/bootstrap/css/bootstrap-yii.css');

        $this->view->headScript()->appendFile('/scripts/jquery-1.7.2.min.js');
        $this->view->headScript()->appendFile('/scripts/global.js?r='.  rand(1, 10000));
        $this->view->headTitle()->setSeparator(' / ');
        $this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');

        $frontController = Zend_Controller_Front::getInstance();
        $this->view->controllerName = $frontController->getRequest()->getControllerName();
        $this->view->actionName = $frontController->getRequest()->getActionName();

        $this->view->headTitle()->setDefaultAttachOrder('PREPEND');
        $this->view->headTitle()->append('Art Social Network');

        $user = App_User::getLoggedUser();
        if ($user) {
            $friendsList = array();
            $this->view->loggedUser = $user;
            foreach ($user->Friends_2 as $friend) {
                $friendsList[] = $friend->friend_id;
            }
            $this->view->friendsList = $friendsList;
            $model = new Model_Messages();
            $this->view->unreadedMessages = $model->getCountUnreadedMessages($user->id);
        }
    }

    protected function _initAcl() {
        $acl = new App_Acl();
        $this->acl = $acl;

        $this->role = App_Auth::getRole();

        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($this->role);
    }

}

