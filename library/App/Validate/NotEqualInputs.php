<?php

/**
 * App_Validate_EqualInputs
 * Validator checks coincidence of two fields
 */

class App_Validate_NotEqualInputs extends Zend_Validate_Abstract {

    /**
     * error label
     * @var const
     */
    const EQUAL = 'stringsEqual';

    /**
     * error text
     * @var array
     */
    protected $_messageTemplates = array(
        self::EQUAL => 'Values must be not equal'
    );

    /**
     * the field name with which it is compared
     * @var string
     */
    protected $_contextKey;

    /**
     * Validator construct
     *
     * @param string $key the field name with which it is compared
     */
    public function __construct($key)
    {
        $this->_contextKey = $key;
    }

    /**
     *
     * Check fields
     * Comparison of value $value with $context[ $this->_contextKey ]
     *
     * @param string $value the value which gives in to validation
     * @param mixed $context the element with which value we compare $value
     * @return bool
     */
    public function isValid($value, $context = null) {

        $value = (string) $value;

        if (is_array($context)) {
            if (isset($context[$this->_contextKey]) && ($value !== $context[$this->_contextKey])) {
                return true;
            }
        }
        else if (is_string($context) && ($value !== $context)) {
            return true;
        }

        $this->_error(self::EQUAL);

        return false;
    }
}
