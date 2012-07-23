<?php
class App_Acl extends Zend_Acl {

    public function __construct() {
        /*
         * Resources
         */
        $this->add(
            new Zend_Acl_Resource(App_Acl_Resources::PUBLICPAGES)
        );
        $this->add(
            new Zend_Acl_Resource(App_Acl_Resources::GUESTPAGES)
        );
        $this->add(
            new Zend_Acl_Resource(App_Acl_Resources::USERSPAGES)
        );
        /*
         * Roles
         */
        $this->addRole(new Zend_Acl_Role(App_Acl_Roles::GUEST));
       
        $this->addRole(
            new Zend_Acl_Role(App_Acl_Roles::USER),
            App_Acl_Roles::GUEST
        );

        /*
         * Rules
         */
        $this->allow(
            App_Acl_Roles::GUEST,
            App_Acl_Resources::PUBLICPAGES
        );
        $this->allow(
            App_Acl_Roles::GUEST,
            App_Acl_Resources::GUESTPAGES
        );
        $this->deny(
            App_Acl_Roles::USER,
            App_Acl_Resources::GUESTPAGES
        );
        $this->allow(
            App_Acl_Roles::USER,
            App_Acl_Resources::USERSPAGES
        );
    }
}
