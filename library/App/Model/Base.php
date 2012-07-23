<?php

/**
 * Base Model class. $fields and $pk should be set in childs.
 *
 */

class App_Model_Base implements App_Model_Interface {

    /**
     * Field list as DomainObjectField => DbField
     * @var array
     */
    protected $fields = array();

    /**
     * Values internal storage. Key is DbField name
     * @var mixed
     */
    protected $_values = array();

    protected $_persistenceKey = null;

    protected $_mapper = null;

    /**
     * @return App_Model_MapperInterface
     */
    public function getMapper()
    {
        return $this->_mapper;
    }

    public function setMapper(App_Model_MapperInterface $mapper)
    {
        $this->_mapper = $mapper;
    }

    public function getPersistenceKey()
    {
        return $this->_persistenceKey;
    }

    public function setPersistenceKey($key)
    {
        $this->_persistenceKey = $key;
    }

    public function getFieldValue($name)
    {
        if ($this->hasField($name)) {
            return array_key_exists($name, $this->_values)?$this->_values[$name]:null;
        }
        else {
            throw new App_Model_Exception();
        }
    }

    public function setFieldValue($name, $value)
    {
        if ($this->hasField($name)) {

            $mapper = $this->getMapper();
            if ($mapper !== null && $this->_values[$name] != $value) {

                if (!$this->getMapper()->eventFieldUpdate($this, $name, $value)) {
                    return false;
                }
            }
            $this->_values[$name] = $value;
        }
    }

    /**
     * Get value of Domain Object property
     * @param string $name Name of Domain Object property
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->getFieldValue($name);
    }

    /**
     * Set value of Domain Object property
     * @param string $name Name of Domain Object property
     * @param mixed $value New value of Domain Object property
     * @return mixed|null
     */
    public function __set($name, $value)
    {
        return $this->setFieldValue($name, $value);
    }

    /**
     * Return array of fields to be saved in db
     * @return mixed
     */
    public function toArray()
    {
        return $this->_values;
    }

    /**
     * Creates Domain Object Model instance
     * @param mixed $data Data as hash with Db Fields as keys
     */
    public function __construct($data = array())
    {
        if (count($this->fields) != count(array_unique($this->fields))) {
            throw new App_Model_Exception('Wrong domain object declaration');
        }
        
        foreach ($this->fields as $do_name) {
            $this->_values[$do_name] = null;
        }
        $this->setDefaultValues();
        foreach ($data as $key => $value) {

            if ($this->hasField($key)) {
                $this->_values[$key] = $value;
            }
        }
    }

    /**
     * Get list of Domain Object properties
     * @return type
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Check, whether Domain Object has specified field
     * @param string $name
     * @return bool
     */
    public function hasField($name)
    {
        return in_array($name, self::getFields());
    }
    
    protected function setDefaultValues() {
        
    }
}
