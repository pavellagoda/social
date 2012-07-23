<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MapperBase
 *
 * @author valery
 */
abstract class App_Model_MapperBase implements App_Model_MapperInterface {

    private $dbAdapter = null;
    private $modelObserver = null;

    public function __construct(Zend_Db_Adapter_Abstract $adapter = null, App_Model_Observer_Interface $observer = null) {
        if (null !== $adapter) {
            $this->dbAdapter = $adapter;
        } else {
            $this->dbAdapter = App_ServiceLocator::DatabaseAdapter();
        }
        if (null !== $observer) {
            $this->modelObserver = $observer;
        } else {
            $this->modelObserver = App_ServiceLocator::ModelObserver();
        }
    }

    public function getFields() {
        return $this->fields;
    }

    public function getPrimaryKey() {
        return $this->pk;
    }

    protected function mapFields(App_Model_Interface $model) {
        $result = array();
        $fieldmap = $this->getFields();
        foreach ($model->toArray() as $do_field => $value) {
            if (array_key_exists($do_field, $fieldmap)) {
                $result[$fieldmap[$do_field]] = $value;
            }
        }
        return $result;
    }

    protected function mapFieldsReverse($data) {
        $fieldmap = $this->getFields();
        $result = array();
        foreach ($fieldmap as $dokey => $dbkey) {
            if (array_key_exists($dbkey, $data)) {
                $result[$dokey] = $data[$dbkey];
            } else {
                $result[$dokey] = null;
            }
        }
        return $result;
    }

    protected function mapField($field) {
        $fieldmap = $this->getFields();
        if (array_key_exists($field, $fieldmap)) {
            return $fieldmap[$field];
        }
    }

    protected function mapFieldReverse($field) {
        $fieldmap = $this->getFields();
        if (in_array($field, $fieldmap)) {
            return array_search($field, $fieldmap);
        }
    }

    public function eventFieldUpdate(App_Model_Interface $model, $field, $value) {
        $this->modelObserver->register($model);
        $this->modelObserver->markDirty($model);
        return true;
    }

    /**
     * @return App_Model_Base
     */
//    public abstract function createModelInstance($data = array());

    /**
     * @return Zend_Db_Adapter_Abstract
     */
    protected function db() {
        return $this->dbAdapter;
    }

    /**
     * @param array $data
     * @return App_Model_Base
     */
    public function constructModel($data) {
        if (is_array($data)) {
            $dataMapped = $this->mapFieldsReverse($data);
            $instance = $this->createModelInstance($dataMapped);
            $instance->setMapper($this);
            $this->modelObserver->register($instance);
            $doPk = $this->mapFieldReverse($this->getPrimaryKey());
            $this->modelObserver->storeValue($instance, $instance->getFieldValue($doPk));
            $this->modelObserver->markUsed($instance);
            $this->modelObserver->markClean($instance);
            return $instance;
        } else {
            return null;
        }
    }

    /**
     * @param array $dataset
     * @return App_Model_Collection 
     */
    protected function constructCollection($dataset) {
        return new App_Model_Collection($dataset, $this);
    }

    /**
     *
     * @param int $id
     * @return App_Model_Collection | App_Model_Base
     */
    public function find($id) {
        $criteria = $this->criteria('*');
        $pk = $this->db()->quoteIdentifier($this->getPrimaryKey());
        $criteria->where($pk . ' = ?', $id);
        $criteria->limit(1);
        return $this->findWithCriteria($criteria);
    }

    /**
     *
     * @param int $limit
     * @return App_Model_Collection 
     */
    public function getAll($limit = false) {
        $criteria = $this->criteria('*');
        $pk = $this->db()->quoteIdentifier($this->getPrimaryKey());
        if ($limit)
            $criteria->limit($limit);
        return $this->findWithCriteria($criteria);
    }

    /**
     * @param array $cols
     * @return Zend_Db_Select
     */
    public function criteria($cols = array('*')) {
        return $this->db()->select()->from($this->table, $cols);
    }

    /**
     * @param Zend_Db_Select $criteria
     * @return App_Model_Base|App_Model_Collection|null
     */
    public function findWithCriteria($criteria) {
        $multiple = $criteria->getPart(Zend_Db_Select::LIMIT_COUNT) != 1;
        $data = $this->executeQuery($criteria);
        if ($multiple) {
            return $this->constructCollection($data);
        } else {
            return $this->constructModel(current($data));
        }
    }

    protected function executeQuery($query) {
        return $query->query(Zend_Db::FETCH_ASSOC)->fetchAll();
    }

    public function save(App_Model_Base $instance) {
        $this->modelObserver->register($instance);
        $instance->setMapper($this);
        if ($this->modelObserver->isDirty($instance)) {
            if ($this->modelObserver->isNew($instance)) {
                $this->db()->insert($this->table, $this->mapFields($instance));
                $doPk = $this->mapFieldReverse($this->getPrimaryKey());
                $newPk = $this->db()->lastInsertId();
                $instance->setFieldValue($doPk, $newPk);
            } else {
                $pkfield = $this->db()->quoteIdentifier($this->getPrimaryKey());
                $pkvalue = $this->modelObserver->getValue($instance);
                $where = $this->db()->quoteInto($pkfield . ' = ?', $pkvalue);
                $this->db()->update($this->table, $this->mapFields($instance), $where);
                $doPk = $this->mapFieldReverse($this->getPrimaryKey());
                $newPk = $instance->getFieldValue($doPk);
            }
            $this->modelObserver->storeValue($instance, $newPk);
            $this->modelObserver->markClean($instance);
            $this->modelObserver->markUsed($instance);
            return $newPk;
        } else {
            return false;
        }
    }

    public function delete(App_Model_Base $instance) {
        $this->modelObserver->register($instance);
        $instance->setMapper($this);
        if (!$this->modelObserver->isNew($instance)) {
            $this->modelObserver->markDirty($instance);
            $this->modelObserver->markNew($instance);
            $pkfield = $this->db()->quoteIdentifier($this->getPrimaryKey());
            $pkvalue = $this->modelObserver->getValue($instance);
            $where = $this->db()->quoteInto($pkfield . ' = ?', $pkvalue);
            $this->db()->delete($this->table, $where);
        }
    }

    /**
     * Truncate table
     * @param bool $sure Set it to true
     */
    public function truncateTable($sure = false) {
        if ($sure) {
            $this->db()->query('TRUNCATE TABLE ' . $this->table);
        }
    }

    /**
     * Return row count in the table
     * @return int
     */
    public function count() {
        $result = $this->db()->select()->
                        from($this->table, array('cnt' => 'COUNT(1)'))
                        ->query()->fetch();
        return $result['cnt'];
    }

}
