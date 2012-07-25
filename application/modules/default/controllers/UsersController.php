<?php

/**
 * IndexController
 *
 * @author pavel
 * @version 1
 */
class UsersController extends modules_default_controllers_ControllerBase
{

    public $ajaxable = array(
        'refresh-captcha' => array('json'),
    );

    public function init()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            parent::init();
        }

        $this->_helper->AjaxContext()->initContext('json');
    }

    public function indexAction()
    {
        $model = new Model_Users();
        $users = $model->getRegistaredUser(App_User::getLoggedUserId());
        $this->view->users = $users;
        $this->view->headScript()->appendFile('/scripts/messages.js?r='.  rand(1, 10000));
        $this->view->headScript()->appendFile('/scripts/jquery.form.js');
        $this->view->headScript()->appendFile('/bootstrap/js/bootstrap-modal.js');
    }

}

