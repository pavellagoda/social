<?php

class ForgotPasswordController extends modules_default_controllers_ControllerBase {

    public function init() {
        /* Initialize action controller here */
        parent::init();
    }

    public function indexAction() {

        $form = new Form_RestorePassword();
        // if user data was submited
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($form->isValid($data)) {
                // collect user data
                if (App_User::restorePassword($form->getValue('Email'))) {
                    // show success message
                }
            } else {
                // show error message
            }
        }
        $this->view->form = $form;
        $this->view->headTitle('Forgot Password Page');
    }

}

