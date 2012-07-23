<?php

class Form_SubmitProject extends Zend_Form {

    public function __construct($options=null) {
        return parent::__construct($options);
    }

    public function init() {

        $oName = new Zend_Form_Element_Text('name');
        $oName->setRequired(true);
        $oName->removeDecorator('HtmlTag');
        $oName->removeDecorator('Label');
        $oName->removeDecorator('Errors');
        $oName->setLabel('Project Name:');
        $this->addElement($oName);

        $oType = new Zend_Form_Element_Radio('application_type_id');
        $types = App_ServiceLocator::ApplicationTypeMapper()->findAll();
        $options = array();
        foreach ($types as $type) {
            $options[$type->id] = $type->name;
        }
        $oType->addMultiOptions($options);
        $oType->removeDecorator('HtmlTag');
        $oType->removeDecorator('Label');
        $oType->removeDecorator('Errors');
        $oType->setLabel('Project Type:');
        $oType->setValue(1);
        $this->addElement($oType);
    }

}