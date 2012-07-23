<?php
class modules_admin_forms_LoginForm extends forms_FormBase
{
	public function init()
	{
		$oEmailElement = new Zend_Form_Element_Text('email');
		$oEmailElement = $this->_initDefaultFiltersAndValidators($oEmailElement);
		$oEmailElement->addValidator(new Zend_Validate_EmailAddress());
		$this->addElement($oEmailElement);

		$oPasswordElement = new Zend_Form_Element_Password('password');
		$oPasswordElement->setRequired(true);
		$this->addElement($oPasswordElement);
	}
}