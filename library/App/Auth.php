<?php
class App_Auth {

    public static function hasIdentity() {
        $auth = Zend_Auth::getInstance();
        return $auth->hasIdentity();
    }

    public static function authenticate($email, $password) {
        $auth = Zend_Auth::getInstance();
        $adapter = new App_Auth_Adapter($email, $password);
        return $auth->authenticate($adapter);
    }

    public static function getIdentity() {
        $auth = Zend_Auth::getInstance();
        return $auth->getIdentity();
    }

    public static function clearIdentity() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
    }

    public static function getRole() {
        if (self::hasIdentity()) {
            return App_Acl_Roles::USER;
//            $user = App_ServiceLocator::UserMapper()->find(self::getIdentity());
//            return ($user->is_admin
//                    ? App_Acl_Roles::ADMIN
//                    : ($user->is_site_editor
//                            ? App_Acl_Roles::SITEEDITOR 
//                            : App_Acl_Roles::DEVELOPER)
//                    );
        } else {
            return App_Acl_Roles::GUEST;
        }
    }

}
