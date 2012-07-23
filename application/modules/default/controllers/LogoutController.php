<?php

class LogoutController extends modules_default_controllers_ControllerBase
{

    public function init()
    {
        /* Initialize action controller here */
    	parent::init();
    }

    public function indexAction()
    {
    	if (App_Auth::hasIdentity())
    	{
    		App_Auth::clearIdentity();
    	}
    	$this->_redirect('/');
    }


}

