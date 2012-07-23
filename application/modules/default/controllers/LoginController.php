<?php

/**
 * Login page controller
 *
 * @author pavel
 *
 */
class LoginController extends modules_default_controllers_ControllerBase {

    protected $_bForLoggedUsersOnly = false;

    public function init() {
        parent::init();
    }

    public function indexAction() {
        if (App_Auth::hasIdentity()) {
            $this->_redirect('/');
        }
        $form = new Form_LoginForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if (App_User::doLogin($form, $request->getPost(), false)) {
                    $this->_helper->redirector('index', 'index');
                }
            }
            $badLoginText = "Login and Password doesn't match";
            $this->view->badLoginText = $badLoginText;
        }
        $this->view->form = $form;
        $this->view->headTitle('Login Page');
    }

}

// end of class
