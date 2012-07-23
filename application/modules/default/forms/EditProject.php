<?php

class Form_EditProject extends Zend_Form {

    private $idType;
    
    public function __construct($options=null, $type_id=1) {
        $this->idType = $type_id;
        return parent::__construct($options);
    }

    public function init() {

        $this->setMethod('post');
        
        $url = new Zend_View_Helper_Url();
        $this->setAction($url->url(array('controller'=> 'project','action'=>'edit')));
        
        $oEl = new Zend_Form_Element_Textarea('overview');
        $oEl->setRequired(true);
        $oEl->setLabel('Project Overview:');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $this->addElement($oEl);

        $oEl = new Zend_Form_Element_Textarea('deliverables');
        $oEl->setRequired(true);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Project Deliverables:');
        $this->addElement($oEl);

        $oEl = new Zend_Form_Element_Select('estimate_budget_id');
        $oEl->setRequired(true);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $values = App_ServiceLocator::EstimateBudgetMapper()->findAll();
        $options = array();
        foreach ($values as $value) {
            $options[$value->id] = $value->value;
        }
        $oEl->addMultiOptions($options);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Project Estimate:');
        $this->addElement($oEl);

        $oEl = new Zend_Form_Element_Select('project_state_id');
        $oEl->setRequired(true);
        $values = App_ServiceLocator::ProjectStateMapper()->findAll();
        $options = array();
        foreach ($values as $value) {
            $options[$value->id] = $value->value;
        }
        $oEl->addMultiOptions($options);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Project State:');
        $this->addElement($oEl);

        $oEl = new Zend_Form_Element_Select('most_important_parameter_id');
        $oEl->setRequired(true);
        $values = App_ServiceLocator::MostImportantParameterMapper()->findAll();
        $options = array();
        foreach ($values as $value) {
            $options[$value->id] = $value->value;
        }
        $oEl->addMultiOptions($options);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Most Important Parameter:');
        $this->addElement($oEl);

        $oEl = new Zend_Form_Element_Select('local_developers_status_id');
        $oEl->setRequired(true);
        $values = App_ServiceLocator::LocalDevelopersStatusMapper()->findAll();
        $options = array();
        foreach ($values as $value) {
            $options[$value->id] = $value->value;
        }
        $oEl->addMultiOptions($options);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Local Developers Status:');
        $this->addElement($oEl);

        $oEl = new Zend_Form_Element_Select('full_time_developers_status_id');
        $oEl->setRequired(true);
        $values = App_ServiceLocator::FullTimeDevelopersStatusMapper()->findAll();
        $options = array();
        foreach ($values as $value) {
            $options[$value->id] = $value->value;
        }
        $oEl->addMultiOptions($options);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Full Time Developers Status:');
        $this->addElement($oEl);

        $oEl = new Zend_Form_Element_Select('project_duration_id');
        $oEl->setRequired(true);
        $values = App_ServiceLocator::ProjectDurationMapper()->findAll();
        $options = array();
        foreach ($values as $value) {
            $options[$value->id] = $value->value;
        }
        $oEl->addMultiOptions($options);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Project Duration:');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Select('project_type_id');
        $oEl->setRequired(true);
        $values = App_ServiceLocator::ProjectTypeMapper()->findByApplicationTypeId($this->idType);
        $options = array();
        foreach ($values as $value) {
            $options[$value->id] = $value->value;
        }
        $oEl->addMultiOptions($options);
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Project Type:');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Checkbox('design');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Design (Wireframes/mockups):');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Checkbox('frontend');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Front-end development:');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Checkbox('backend');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Back-end development:');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Textarea('other_skills');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Other Skills:');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Textarea('team_attributes');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Team Attributes:');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Text('url');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Reference Url:');
        $oEl->addValidator(new Zend_Validate_Hostname());
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Textarea('description');
        $oEl->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        $oEl->setLabel('Reference Description:');
        $this->addElement($oEl);
        
        $oEl = new Zend_Form_Element_Hidden('validate');
        $oEl->setValue(0);
        $this->addElement($oEl);
    }

}