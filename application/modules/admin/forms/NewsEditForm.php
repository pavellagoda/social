<?php

/**
 * modules_admin_forms_NewsEditForm
 * 
 * @author valery
 * @version 1
 */
	
class modules_admin_forms_NewsEditForm extends Zend_Form
{

	public function init() 
	{
		$oEl = new Zend_Form_Element_Text('title');
                $oEl->setLabel('Заголовок:');
		$oEl->setRequired(true);
		$this->addElement($oEl);
		
		$oEl = new Zend_Form_Element_Textarea('short');
                $oEl->setLabel('Короткое описание:');
                $oEl->setAttrib('class', 'tiny');
		$oEl->setRequired(true);
		$this->addElement($oEl);
		
		$oEl = new Zend_Form_Element_Textarea('full');
                $oEl->setLabel('Полный текст новости:');
                $oEl->setAttrib('class', 'tiny');
		$oEl->setRequired(false);
		$this->addElement($oEl);
                
                $oEl = new Zend_Form_Element_Submit('submit');
                $oEl->setLabel('Сохранить');
		$oEl->setRequired(false);
		$this->addElement($oEl);
	}

}

