<?php

/**
 * IndexController
 *
 * @author pavel
 * @version 1
 */
class IndexController extends modules_default_controllers_ControllerBase
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
        $request = $this->getRequest();
        $this->view->headTitle('Home Page');
    }

}

