<?php

abstract class App_Validate_Db_Abstract extends Zend_Validate_Abstract
{
    protected $_table;
    protected $_field;

    const ERROR_RECORD_FOUND    = 'recordFound';
    const ERROR_NO_RECORD_FOUND    = 'noRecordFound';

    protected $_messageTemplates = array(
        self::ERROR_RECORD_FOUND    => "A record matching '%value%' was found",
        self::ERROR_NO_RECORD_FOUND    => "A record matching '%value%' not found",
    );

    public function __construct($table, $field) {
        if(is_null(Doctrine::getTable($table)))
            return null;

        if(!Doctrine::getTable($table)->hasColumn($field))
            return null;

        $this->_table = Doctrine::getTable($table);
        $this->_field = $field;
    }
}