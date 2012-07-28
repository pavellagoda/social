<?php

/**
 * IndexController
 *
 * @author pavel
 * @version 1
 */
class MessagesController extends modules_default_controllers_ControllerBase
{

    public function init()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            parent::init();
        }
    }

    public function indexAction()
    {
        $model = new Model_Messages();
        $messages = Doctrine::getTable('Model_Messages')->findByRecepientId(App_User::getLoggedUserId());
        $this->view->messages = $messages;
    }
    
    public function viewAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id', 0);
        if(0==$id) {
            $this->_helper->_redirector('index');
        }
        $message = Doctrine::getTable('Model_Messages')->findOneById($id);
        $message->is_readed = 1;
        $message->save();
        $this->view->message = $message;
    }

    public function sendAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $this->getRequest()->getPost();
            $message = new Model_Messages();
            $message->recepient_id = $post['recepient'];
            $message->sender_id = $post['sender'];
            $message->message = $post['content'];
            $message->save();
            echo 'Message has sent';
            die;
        }
        echo "Message hasn't sent";
        die;
    }

}

