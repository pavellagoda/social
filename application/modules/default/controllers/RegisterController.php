<?php

class RegisterController extends modules_default_controllers_ControllerBase {

    public $_contentLayout = 'short';

    public function init() {
        /* Initialize action controller here */
        parent::init();
    }

    public function indexAction() {
        $this->sess = new Zend_Session_Namespace('Registration');

        // init form
        $form = new Form_Registration();

        // if user data was submited
        if ($this->getRequest()->isPost()) {

            $data = $form->preValidation($this->getRequest()->getPost());
            if ($form->isValid($data)) {

                // store inputed data
                if(App_User::register($form->getValues()))
                    $this->_helper->redirector('index', 'index');
                $front = Zend_Controller_Front::getInstance();
                $front->registerPlugin(new App_Controller_Plugin_FlashMessenger());
                // wellcome message
                $this->_helper->FlashMessenger->setNamespace('messages')->addMessage('Greetings!');
            } else {

                // get list of errors
                $this->view->errors = $form->getErrors();
                // populate into form correct data
//                print_r($this->view->errors); die;
                $form->populate($form->getValues());
            }
        }

        // put form data in view
        $this->view->form = $form;
        $this->view->headTitle('Registartion Page');
    }

}

