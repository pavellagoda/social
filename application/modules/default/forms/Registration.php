<?php

class Form_Registration extends Zend_Form {

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
        $this->addElementPrefixPath('App_Validate_Db', 'App/Validate/Db', 'validate');

        $this->setAttrib('class', 'register');
        $this->setMethod('post');

        $url = new Zend_View_Helper_Url();
        $this->setAction($url->url(array('controller'=> 'register','action'=>'index'),'default'));

        // full name
        $fn = $this->createElement('text', 'fullname');
        $fn ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->removeDecorator('Errors')
            ->setAttrib('maxlength' , 45)
            ->addFilter('StringTrim')
            ->setLabel('Full Name')
//            ->addValidator('Alnum', true, array(true))
//            ->addValidator('StringLength', true, array(2,45))
            ->setRequired(true)
        ;
        $this->addElement($fn);

        // user name
        $un = $this->createElement('text', 'username');
        $un ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->removeDecorator('Errors')
            ->setAttrib('maxlength' , 45)
                ->setLabel('Username')
            ->addFilter('StringTrim')
            ->addValidator('Alnum', true, array(true))
            ->addValidator('StringLength', true, array(2,45))
            ->addValidator('NoRecordExists', true, array('Model_Users', 'username'))
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
//             ->addValidator('Alnum', true, array(true))
             ->addValidator('StringLength', true, array(6,30))
             ->addValidator('Password')
             ->setRequired(true)
        ;
        $this->addElement($pwd);

        // password approve
        $pwd2 = $this->createElement('password', 'password_approve');
        $pwd2 ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->removeDecorator('Errors')
            ->setAttrib('maxlength' , 30)
            ->addFilter('StringTrim')
                ->setLabel('Confirm Password')
//            ->addValidator('Alnum', true, array(true))
            ->addValidator('StringLength', true, array(6,30))
            ->addValidator('Password')
            ->addValidator('EqualInputs', true, array('password'))
            ->setRequired(true)
        ;
        $this->addElement($pwd2);

        // e-mail
        $email = $this->createElement('text', 'email');
        $email ->removeDecorator('HtmlTag')
               ->removeDecorator('Label')
               ->removeDecorator('Errors')
               ->setAttrib('maxlength' , 255)
                ->setLabel('E-mail')
               ->addFilter('StringTrim')
               ->addValidator('StringLength', true, array(6,255))
               ->addValidator('EmailAddress', true)
               ->addValidator('NoRecordExists', true, array('Model_Users', 'email'))
               ->setRequired(true)
        ;
        $this->addElement($email);

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessages(array(Zend_Validate_NotEmpty::IS_EMPTY  => 'agree_rules'));

        // Checkbox element "agree with rules".
        $agreeRules = $this->createElement('checkbox', 'agree_rules');
        $agreeRules ->removeDecorator('HtmlTag')
                    ->removeDecorator('Label')
                    ->removeDecorator('Errors')
                    ->setLabel('I agree with terms and services')
                    ->addFilter('Int')
                    ->addValidator($validatorNotEmpty)
                    ->setRequired(true)
        ;
        $this->addElement($agreeRules);
    }
}
