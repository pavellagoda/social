<?php

/**
 * modules_admin_forms_ObjectEditForm
 * 
 * @author pavel
 * @version 1
 */
	
class modules_admin_forms_StaticpageEditForm extends Zend_Form
{

	public function init() 
	{
		$oEl = new Zend_Form_Element_Text('title');
                $oEl->setLabel('Страница:');
		$oEl->setRequired(true);
		$this->addElement($oEl);
		
		$oEl = new Zend_Form_Element_Textarea('content');
                $oEl->setLabel('Текст страницы:');
                $oEl->setAttrib('class', 'tiny');
		$oEl->setRequired(false);
		$this->addElement($oEl);
                
                $oEl = new Zend_Form_Element_Submit('submit');
                $oEl->setLabel('Сохранить');
		$oEl->setRequired(false);
		$this->addElement($oEl);
	}

}

