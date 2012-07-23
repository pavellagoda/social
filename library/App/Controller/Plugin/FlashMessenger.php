<?php

/**
 * App_Controller_Plugin_FlashMessenger
 *
 * The plugin checks the messages on the successful results and if they are,
 * creates a named segment to display them in the layout
 */
class App_Controller_Plugin_FlashMessenger extends Zend_Controller_Plugin_Abstract {

    /**
     * Catching postDispatch events
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return mixed
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        // init FlashMessenger helper and get messages
        $actionHelperFlashMessenger = new Zend_Controller_Action_Helper_FlashMessenger();
        $messagesSuccess = $actionHelperFlashMessenger->setNamespace('messages')->getMessages();

        // if no messages or scheduling process is still ongoing, just leave the plug-in
        if (empty($messagesSuccess) || !$request->isDispatched()) {
            return;
        }

        // getting Zend_Layout object
        $layout = Zend_Layout::getMvcInstance();
        // getting View object
        $view = $layout->getView();
        // add variable into View
        $view->messages = $messagesSuccess;

        // set the view object to the new variables and render the view script to segment messages
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer
            ->setView($view)
            ->renderScript('messages.phtml', 'messages')
        ;
    }
}