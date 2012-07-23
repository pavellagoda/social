<?php
/**
 * Error controller
 *
 * @author pavel
 *
 */

class ErrorController extends modules_default_controllers_ControllerBase
{
        protected $_bForLoggedUsersOnly = false;

        public function init()
        {
        	parent::init();
        	$this->layout->setLayout('error');
        }
        
        public function errorAction()
        {
                $errors = $this->_getParam('error_handler');

                switch ($errors->type) {
                        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
                        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :

                                $this->getResponse()->setHttpResponseCode(404);
                                $this->view->message = 'Page not found';
                                break;

                        default :
                                $this->getResponse()->setHttpResponseCode(500);
                                $this->view->message = 'Application error';
                                break;
                }
                $this->view->exception = $errors->exception;
                $this->view->request = $errors->request;
                $this->view->env = APPLICATION_ENV;
                
                $this->view->title = 'Error';
                
                //$this->_helper->viewRenderer->setNoRender();
//                $this->_helper->layout->disableLayout();
        }
        
        public function projectnotfoundAction() {
        	$projectSlug = $this->_getParam('projectslug');
        	$this->view->projectSlug = $projectSlug;
        }
}