<?php

/**
 * App_Validate_Password
 */
class App_Validate_Password extends Zend_Validate_Abstract {
    /**
     * error label
     * @var const
     */
    const INVALID = 'passwordInvalid';

    /**
     * @var const
     */
    const INVALID_LENGTH = 'passwordBadLength';

    /**
     * error text
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => 'Value does not appear to be a valid password',
        self::INVALID_LENGTH => 'Value should have more than 6 and less then 30 symbols'
    );

    /**
     * Check password
     *
     * @param string $value checking value
     * @return bool
     */
    public function isValid($value)
    {
        // set value into error text
        $this->_setValue($value);

        // ckech length
        $validatorStringLength = new Zend_Validate_StringLength(5, 30);

        // checking for valid characters
        if (!preg_match("/^[~!@#\\$%\\^&\\*\\(\\)\\-_\\+=\\\\\/\\{\\}\\[\\].,\\?<>:;a-z0-9]*$/i", $value)) {

            // what kind of error occurred
            $this->_error(self::INVALID);
            return false;
        }
        elseif (!$validatorStringLength->isValid($value)) {

            $this->_error(self::INVALID_LENGTH);
            return false;
        }

        return true;
    }
}