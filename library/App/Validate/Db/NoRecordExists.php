<?php

class App_Validate_Db_NoRecordExists extends App_Validate_Db_Abstract
{
    public function isValid($value)
    {
        $valid = true;

        $this->_setValue($value);

        $funcName = 'findBy' . $this->_field;

        if(count($this->_table->$funcName($value))>0) {
            $valid = false;
            $this->_error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}