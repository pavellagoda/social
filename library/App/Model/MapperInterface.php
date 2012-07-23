<?php

interface App_Model_MapperInterface {

    /**
     * @return App_Model_Base
     */
    public function createModelInstance($data = array());

    public function eventFieldUpdate(App_Model_Interface $model, $field, $value);

    public function getFields();

    public function getPrimaryKey();
}
