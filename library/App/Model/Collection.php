<?php

/**
 * Collection of domain objects
 *
 * @author valeriy
 */
class App_Model_Collection implements Iterator, Countable {
    
    protected $raw = array();
    /**
     * @var App_Model_MapperBase
     */
    protected $mapper;
    protected $total = 0;
    
    private $result;
    private $pointer = 0;
    private $objects = array();
    
    public function __construct($raw = null, $mapper = null) {
        if (is_array($raw) && !is_null($mapper)) {
            $this->raw = $raw;
            $this->total = count($raw);
        }
        $this->mapper = $mapper;
    }

    public function getRow($index) {
        if ($index >= $this->total || $index < 0) {
            return null;
        }
        if (isset($this->objects[$index])) {
            return $this->objects[$index];
        }
        if (isset($this->raw[$index])) {
            $instance = $this->mapper->constructModel($this->raw[$index]);
            $this->objects[$index] = $instance;
            return $this->objects[$index];
        }
        
    }
    
    public function current() {
        return $this->getRow($this->pointer);
    }

    public function key() {
        return $this->pointer;
    }

    public function next() {
        $row = $this->getRow($this->pointer);
        if ($row) {
            $this->pointer++;
        }
        return $row;
    }

    public function rewind() {
        $this->pointer = 0;
    }

    public function valid() {
        return (!is_null($this->current()));
    }

    public function count() {
        return count($this->raw);
    }

    
}

?>
