<?php
class controllers_ControllerBase extends Zend_Controller_Action
{

	public $_contentLayout = 'full';
	
	public function init()
	{
		$oZendSession = Zend_Registry::getInstance()->get('Zend_Session_Namespace');
	
		$this->layout = Zend_Layout::getMvcInstance();
		$this->view->addBasePath(APPLICATION_PATH.'/views');
		$this->view->addBasePath(APPLICATION_PATH.'/layouts');
		if ($this->getRequest()->getModuleName() != 'default')
		{
			$this->layout->setLayoutPath(Zend_Controller_Front::getInstance()->getModuleDirectory().'/layouts');
		}

		$this->layout->setLayout('layout');
		
//		$this->view->headScript()->appendFile('/js/lib/jquery-1.4.2.min.js');
//		$this->view->headScript()->appendFile('/js/lib/swfobject.js');
//		
//		$this->view->headLink()->appendStylesheet('/css/common/reset.css');
//		$this->view->headLink()->appendStylesheet('/css/common/960.css');
//		$this->view->headTitle()->setSeparator(' / ');
		
	}
	
}
                
