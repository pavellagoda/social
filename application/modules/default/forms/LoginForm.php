<?php

class Form_LoginForm extends Form_BaseForm
{

    public function __construct($options=null)
    {
        return parent::__construct($options);
    }

    public function preValidation($data)
    {

        return $data;
    }

    public function init()
    {
        $this->setAttrib('class', 'login');
        $this->setMethod('post');

        $url = new Zend_View_Helper_Url();
        $this->setAction($url->url(array('controller' => 'login', 'action' => 'index')));

        // user name
        $un = $this->createElement('text', 'UserName');
        $un->setLabel('Login')
                ->addFilter('StringTrim')
                ->setRequired(true)
                ->addValidators(array(
                    array('NotEmpty', true, array('messages' => array(
                                'isEmpty' => 'User Name is required field!',
                        )))))
        ;
        $this->addElement($un);

        // password
        $pwd = $this->createElement('password', 'Password');
        $pwd->setAttrib('maxlength', 30)
                ->setLabel('Password')
                ->addFilter('StringTrim')
                ->setRequired(true)
                ->addValidators(array(
                    array('NotEmpty', true, array('messages' => array(
                                'isEmpty' => 'Password can not be empty!',
                        )))))
        ;
        $this->addElement($pwd);
        $this->removeDecoratorsForAll();
    }

}
