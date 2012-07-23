<?php

/**
 * Login controller
 *
 * @author pavel
 *
 */
class Admin_LogoutController extends modules_admin_controllers_ControllerBase {

    protected $_bForLoggedUsersOnly = true;
    
//----------------------------------------------------------------------------------------------------
	public function indexAction ()
	{
		FW_Admin_Auth::logout();
		$this->_redirect('/admin/');
	}
	

//----------------------------------------------------------------------------------------------------
}