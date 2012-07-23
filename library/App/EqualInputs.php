<?php

/**
 * App_Validate_EqualInputs
 * 
 * 
 * @author pavel
 */

class App_EqualInputs extends Zend_Validate_Abstract {
    
    /**
     * ����� ������
     * @var const 
     */
    const NOT_EQUAL = 'stringsNotEqual';
    
    /**
     * ����� ������
     * @var array 
     */
    protected $_messageTemplates = array(
        self::NOT_EQUAL => 'Strings are not equal'
    );
    
    /**
     * �������� ����, � ������� ����������
     * @var string 
     */
    protected $_contextKey;
    
    /**
     * ����������� ����������
     *
     * @param string $key �������� ����, � ������� ����������
     */
    public function __construct($key) {

        $this->_contextKey = $key;
    }
    
    
    /**
     * 
     * ��������� �����
     * 
     * ��������� �������� $value � $context[ $this->_contextKey ]
     * 
     * @param string $value �������� ������� ��������� ���������
     */
    public function isValid($value, $context = null) {
        
        $value = (string) $value;

        if (is_array($context)) {
            if (isset($context[$this->_contextKey]) && ($value === $context[$this->_contextKey])) {
                return true;
            }
        }
        else if (is_string($context) && ($value === $context))  {
            return true;
        }
        
        $this->_error(self::NOT_EQUAL);
        
        return false;
    }
}