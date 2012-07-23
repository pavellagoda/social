<?php

require_once 'BaseController.php';

class IndexController extends BaseController {

    public function init()
    {

    }

    public function indexAction()
    {
        $this->view->user = $this->user;
    }

}
