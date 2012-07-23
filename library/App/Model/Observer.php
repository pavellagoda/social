<?php

/**
 * Description of Observer
 *
 * @author valeriy
 */
class App_Model_Observer implements App_Model_Observer_Interface {
    
    protected $firstFreeKey = 1;
    /**
     * @var mixed|App_Model_Observer_State
     */
    protected $storage = array();
    
    protected function isRegistered(App_Model_Interface $model) {
        return $model->getPersistenceKey() && array_key_exists($model->getPersistenceKey(), $this->storage) ;
    }
    
    public function getNewKey() {
        return $this->firstFreeKey++;
    }
    
    public function isDirty(App_Model_Interface $model) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->getDirty();
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }

    public function isNew(App_Model_Interface $model) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->getNew();
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }

    public function markClean(App_Model_Interface $model) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->setDirty(false);
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }

    public function markDirty(App_Model_Interface $model) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->setDirty(true);
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }

    public function markNew(App_Model_Interface $model) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->setNew(true);
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }

    public function markUsed(App_Model_Interface $model) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->setNew(false);
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }

    public function register(App_Model_Interface $model) {
        if (!$this->isRegistered($model)) {
            $key = $this->getNewKey();
            $state = new App_Model_Observer_State();
            $this->storage[$key] = $state;
            $model->setPersistenceKey($key);
            return true;
        }
    }
    
    public function getValue(App_Model_Interface $model) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->getExtra();
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }

    public function storeValue(App_Model_Interface $model, $value) {
        if ($this->isRegistered($model)) {
            return $this->storage[$model->getPersistenceKey()]->setExtra($value);
        } else {
            throw new App_Model_Observer_Exception('Model instance not registered');
        }
    }


}
