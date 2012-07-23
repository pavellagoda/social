<?php

/**
 * Login controller
 *
 * @author pavel
 *
 */
class Admin_LoginController extends modules_admin_controllers_ControllerBase {

    protected $_bForLoggedAdminOnly = false;
    
//----------------------------------------------------------------------------------------------------
	public function indexAction ()
	{
		if ( FW_Admin_Auth::isLogged() )
		{
			$this->_redirect('/admin');
		}
		
		$request = $this->getRequest();
		
		$form = new modules_admin_forms_LoginForm();
		
		if ($request->isPost())
		{
			if ($form->isValid($request->getPost()))
			{
				
				FW_Admin_Auth::doLogin($form, $request->getPost(), false);
				
				$this->_redirect('/admin');
			}
		}
		
		$this->view->form = $form;
	}
	

//----------------------------------------------------------------------------------------------------
}