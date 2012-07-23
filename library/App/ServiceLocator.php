<?php

/**
 * Object registry
 *
 */
class App_ServiceLocator {

    protected static $storage = array();

    public static function gs() {
        return self::$storage;
    }

    public static function __callStatic($name, $args) {
        return self::load($name);
    }

    public static function store($name, $obj) {
        $parts = explode('::', $name, 2);
        if (count($parts) > 1) {
            $name = $parts[1];
        }
        self::$storage[$name] = $obj;
    }

    public static function isStored($name) {
        $parts = explode('::', $name, 2);
        if (count($parts) > 1) {
            $name = $parts[1];
        }
        return array_key_exists($name, self::$storage);
    }
    
    public static function load($name) {
        $parts = explode('::', $name, 2);
        if (count($parts) > 1) {
            $name = $parts[1];
        }
        if (array_key_exists($name, self::$storage)) {
            return self::$storage[$name];
        } else {
            throw new Exception('Nothing named key "' . $name . '" found by locator.');
        }
    }

    /**
     * @return Zend_Db_Adapter_Abstract
     */
    public static function DatabaseAdapter() {
        return self::load(__METHOD__);
    }

    /**
     * @return Model_Users
     */
    public static function UserMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_Users'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return Model_Projects
     */
    public static function ProjectMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_Projects'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return Model_EstimateBudgetMapper
     */
    public static function EstimateBudgetMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_EstimateBudgets'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return Model_LocalDevelopersStatusMapper
     */
    public static function LocalDevelopersStatusMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_LocalDevelopersStatuses'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return Model_FullTimeDevelopersStatusMapper
     */
    public static function FullTimeDevelopersStatusMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_FullTimeDevelopersStatuses'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return Model_ProjectDurationMapper
     */
    public static function ProjectDurationMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_ProjectDurations'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return Model_MostImportantParameterMapper
     */
    public static function MostImportantParameterMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_MostImportantParameters'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return ProjectStateMapper
     */
    public static function ProjectStateMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_ProjectStates'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return ProjectTypeMapper
     */
    public static function ProjectTypeMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_ProjectTypes'));
        }
        return self::load(__METHOD__);
    }
    
    /**
     * @return Model_ApplicationTypes
     */
    public static function ApplicationTypeMapper() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, Doctrine::getTable('Model_ApplicationTypes'));
        }
        return self::load(__METHOD__);
    }

    /**
     * @return App_Model_Observer_Interface
     */
    public static function ModelObserver() {
        if (!self::isStored(__METHOD__, self::$storage)) {
            self::store(__METHOD__, new App_Model_Observer());
        }
        return self::load(__METHOD__);
    }

}
