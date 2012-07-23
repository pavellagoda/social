<?php

class Form_RestorePassword extends Zend_Form {

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

        $url = new Zend_View_Helper_Url();
        $this->setAction($url->url(array('controller'=> 'forgot-password','action'=>'index'),'default'));
        $this->setMethod('post');

        // e-mail
        $email = $this->createElement('text', 'Email');
        $email ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->removeDecorator('Errors')
            ->setAttrib('maxlength' , 255)
            ->addFilter('StringTrim')
            ->addValidator('StringLength', true, array(6,255))
            ->addValidator('EmailAddress', true)
            ->addValidator('Db_RecordExists', true, array('Model_Users', 'email'))
            ->setRequired(true)
        ;
        $this->addElement($email);

        $submit = $this->createElement('submit', 'submit');
        $this->addElement($submit);
    }
}
