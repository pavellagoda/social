<?php

class App_Auth_Adapter implements Zend_Auth_Adapter_Interface {
    const NOT_FOUND_MSG = "Account not found";
    const BAD_PW_MSG = "Password is invalid";

    /**
     * @var string
     */
    protected $_username = "";

    /**
     * @var string
     */
    protected $_password = "";

    /**
     * @var Model_User
     */
    protected $_user;
    
    private $_salt = 'P@E*a&r9Xat4ENuc';
    
    private $NOT_FOUND = 1;
    private $WRONG_PW  = 2;

    public function __construct($username, $password) {
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        try {
            $user = App_ServiceLocator::UserMapper()->findOneByUsername($this->_username);
            if ($user) {
                if ($user->password == App_StringGenerator::encodePassword($this->_password)) {
                    $this->_user = $user;
                    return $this->createResult(Zend_Auth_Result::SUCCESS);
                }
                throw new Exception($this->WRONG_PW);
            }
            throw new Exception($this->NOT_FOUND);
            
        } catch (Exception $e) {
            if ($e->getMessage() == $this->WRONG_PW)
                return $this->createResult(
                                Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, array(self::BAD_PW_MSG)
                );
            if ($e->getMessage() == $this->NOT_FOUND)
                return $this->createResult(
                                Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, array(self::NOT_FOUND_MSG)
                );
            return $this->createResult(
                            Zend_Auth_Result::FAILURE
            );
        }
    }

    private function createResult($code, $messages = array()) {
        $identity = $this->_user ? $this->_user->id : null;
        return new Zend_Auth_Result($code, $identity, $messages);
    }

}
