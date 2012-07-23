<?php
class Form_ForgotPasswordForm extends Zend_Form
{
	public function init()
	{
		$oEmailElement = new Zend_Form_Element_Text('email');
		$oEmailElement -> setRequired(true);
		$oEmailElement->setLabel('Enter your e-mail:');
		$oEmailElement->addValidator(new Zend_Validate_EmailAddress());
		$this->addElement($oEmailElement);
		
		$oEl = new Zend_Form_Element_Submit('submit');
		$oEl->setLabel('Restore password');
		$this->addElement($oEl);
	}
}