<?php

/**
 * IndexController
 *
 * @author pavel
 * @version 1
 */
class FirmsController extends modules_default_controllers_ControllerBase {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->view->firms = Doctrine::getTable('Model_Firms')->findAll();
        $likeFirms = Doctrine::getTable('Model_Favorites')->findByUserId(App_User::getLoggedUserId());
        $firmList = array();
        foreach ($likeFirms as $firm) {
            $firmList[] = $firm->firm_id;
        }
        $this->view->firmList = $firmList;
    }
    public function viewAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id', 0);
        if(0==$id) {
            $this->_helper->_redirector('index');
        }
        $firm = Doctrine::getTable('Model_Firms')->findOneById($id);
        $this->view->firm = $firm;
        $this->view->isFavourite = Doctrine::getTable('Model_Favorites')->findByUserIdAndFirmId(App_User::getLoggedUserId(), $id)->count();
    }
    
    public function favoritesAction() {
        $this->indexAction();
        $model = new Model_Firms();
        $this->view->firms = $model->findByUserId(App_User::getLoggedUserId());
    }

}

