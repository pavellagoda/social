<?php

/**
 * Description of Interface
 *
 */
interface App_Model_Interface {

    public function getPersistenceKey();

    public function setPersistenceKey($key);

    public function getFieldValue($name);

    public function setFieldValue($name, $value);

    public function toArray();

}
