<?php

interface App_Model_Observer_Interface {

    public function register(App_Model_Interface $model);

    public function markDirty(App_Model_Interface $model);

    public function markClean(App_Model_Interface $model);

    public function markNew(App_Model_Interface $model);

    public function markUsed(App_Model_Interface $model);

    public function isDirty(App_Model_Interface $model);

    public function isNew(App_Model_Interface $model);

    public function storeValue(App_Model_Interface $model, $value);

    public function getValue(App_Model_Interface $model);

}
