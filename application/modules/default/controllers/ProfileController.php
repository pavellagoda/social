<?php

/**
 * IndexController
 *
 * @author pavel
 * @version 1
 */
class ProfileController extends modules_default_controllers_ControllerBase {

    protected $_bForLoggedUsersOnly = true;
    
    public function init() {
        /* Initialize action controller here */
        parent::init();
    }
    
    public function indexAction() {
        $form = new Form_UpdateProfile();
        $user = App_User::getLoggedUser();
        if(!$user)
            $this->_helper->redirector('index', 'index');
        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $form->preValidation($request->getPost());
            if($form->isValid($request->getPost())) {
                $user->email = $form->getValue('email');
                $user->fullname = $form->getValue('fullname');
                $user->username = $form->getValue('username');
                $user->password = App_StringGenerator::encodePassword($form->getValue('password'));
                $user->save();
            } else {
                $this->view->errors = $form->getErrors();
            }
        }
        $form->populate($user->toArray());
        $this->view->form = $form;
        $this->view->headTitle('Profile');
    }

}

