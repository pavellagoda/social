<?php

class Form_UpdateProfile extends Zend_Form {

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
        parent::init();

        $this->addElementPrefixPath('App_Validate', 'App/Validate/', 'validate');

        $this->setAttrib('class', 'register');
        $this->setMethod('post');

        $url = new Zend_View_Helper_Url();
        $this->setAction($url->url(array('controller'=> 'profile','action'=>'index'),'default'));

        // full name
        $fn = $this->createElement('text', 'fullname');
        $fn ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->removeDecorator('Errors')
            ->setAttrib('maxlength' , 45)
            ->setLabel('Full Name')
            ->addFilter('StringTrim')
            ->addValidator('Alnum', true, array(true))
            ->addValidator('StringLength', true, array(2,45))
            ->setRequired(true)
        ;
        $this->addElement($fn);

        // user name
        $un = $this->createElement('text', 'username');
        $un ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->removeDecorator('Errors')
            ->setAttrib('maxlength' , 45)
            ->setAttrib('readonly' , 'readonly')
            ->setLabel('username')
            ->addFilter('StringTrim')
            ->addValidator('Alnum', true, array(true))
            ->addValidator('StringLength', true, array(2,45))
            ->setRequired(true)
        ;
        $this->addElement($un);

        // password
        $pwd = $this->createElement('password', 'password');
        $pwd ->removeDecorator('HtmlTag')
             ->removeDecorator('Label')
             ->removeDecorator('Errors')
             ->setAttrib('maxlength' , 30)
             ->setLabel('Password')
             ->addFilter('StringTrim')
             ->addValidator('StringLength', true, array(6,30))
             ->addValidator('Password')
             ->setRequired(true)
        ;
        $this->addElement($pwd);

        // password approve
        $pwd2 = $this->createElement('password', 'passwordapprove');
        $pwd2 ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->removeDecorator('Errors')
            ->setAttrib('maxlength' , 30)
            ->addFilter('StringTrim')
            ->setLabel('Confirm Password')
            ->addValidator('StringLength', true, array(6,30))
            ->addValidator('Password')
//            ->addValidator('EqualInputs', true, array('password'))
            ->setRequired(true)
        ;
        $this->addElement($pwd2);

        // e-mail
        $email = $this->createElement('text', 'email');
        $email ->removeDecorator('HtmlTag')
               ->removeDecorator('Label')
               ->removeDecorator('Errors')
               ->setAttrib('maxlength' , 255)
               ->setAttrib('readonly' , 'readonly')
               ->setLabel('E-mail')
               ->addFilter('StringTrim')
               ->addValidator('StringLength', true, array(6,255))
               ->addValidator('EmailAddress', true)
               ->setRequired(true)
        ;
        $this->addElement($email);

    }
}
