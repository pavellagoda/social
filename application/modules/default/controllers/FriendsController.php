<?php

/**
 * IndexController
 *
 * @author pavel
 * @version 1
 */
class FriendsController extends modules_default_controllers_ControllerBase
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
    
    public function indexAction() {
        $this->view->friends = Doctrine::getTable('Model_Friends')->findByUserId(App_User::getLoggedUserId());
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id', 0);
        if(!$id) {
            $this->_redirect($_SERVER['HTTP_REFERER']);
        }
        $model = new Model_Friends();
        $model->user_id = App_User::getLoggedUserId();
        $model->friend_id = $id;
        $model->save();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }
    public function removeAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id', 0);
        if(!$id) {
            $this->_redirect($_SERVER['HTTP_REFERER']);
        }
        $model = Doctrine::getTable('Model_Friends')->findOneByUserIdAndFriendId(App_User::getLoggedUserId(), $id)->delete();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

}

