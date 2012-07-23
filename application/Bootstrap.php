<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    protected function _initFrontController() {
        $front = Zend_Controller_Front::getInstance();

        $front->setControllerDirectory(
                array(
                    'default' => APPLICATION_PATH .
                    '/modules/default/controllers',
                    'admin' => APPLICATION_PATH .
                    '/modules/admin/controllers'
                )
        );

        $front->addModuleDirectory(APPLICATION_PATH . '/modules');

        $front->setDefaultModule('default');

        $router = $front->getRouter();
        $this->bootstrap('autoloader');

        return $front;
    }

    protected function _initAutoloader() {
        $autoloader = Zend_Loader_Autoloader::getInstance();

        $resources = new Zend_Loader_Autoloader_Resource(
                        array(
                            'basePath' => APPLICATION_PATH, //. '/../public/applicationform/',
                            'namespace' => ''
                        )
        );

        $resources->addResourceType(
                'model', 'models/', 'Model'
        );

        $resources->addResourceType(
                'form', 'modules/default/forms/', 'Form'
        );
        
        $autoloader->pushAutoloader($resources);
        $autoloader->setFallbackAutoloader(true);
//        print_r($resources); die;
        return $autoloader;
    }

    protected function _initDoctrine() {
        $this->getApplication()->getAutoloader()
                ->pushAutoloader(array('Doctrine', 'autoload'));

        $doctrineConfig = $this->getOption('doctrine');
        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(
                Doctrine::ATTR_MODEL_LOADING, $doctrineConfig['model_autoloading']
        );

        $manager->setCharset('utf8');
        $manager->setCollate('utf8_general_ci');

//		Doctrine_Core::loadModels($doctrineConfig['models_path']);

        $conn = Doctrine_Manager::connection(
                        $doctrineConfig['dsn'], 'doctrine'
        );

        $conn->setCharset('utf8');
        $conn->setCollate('utf8_general_ci');

        $conn->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
        return $conn;
    }

    protected function _initServiceLocator() {
//        $this->bootstrap('db');
//        App_ServiceLocator::store('DatabaseAdapter', Zend_Db_Table::getDefaultAdapter());
    }

    protected function _initConfig() {
        $oConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
        $oRegistry = Zend_Registry::getInstance();
        $oRegistry->set('config', $oConfig);
    }

    protected function _initTranslation() {
        $translate = new Zend_Translate('array', APPLICATION_PATH . '/languages');
        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Validate_Abstract::setDefaultTranslator($translate);
        Zend_Form::setDefaultTranslator($translate);
    }

    protected function _initSession() {
        $oRegistry = Zend_Registry::getInstance();
        $oConfig = $oRegistry->get('config')->session;
        Zend_Session::setOptions($oConfig->toArray());
        $oRegistry->set('Zend_Session_Namespace', new Zend_Session_Namespace());
    }

    protected function _initLayout() {
        return Zend_Layout::startMvc();
    }

    protected function _initNavigation() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();

        $navigation = new Zend_Navigation();
        $pages = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'main');
        $navigation->addPages($pages);
        $view->navigation($navigation);
        $view->mainNavigation = $navigation;

        $navigation = new Zend_Navigation();
        $pages = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'additional');
        $navigation->addPages($pages);
        $view->additionalNavigation = $navigation;

        $navigation = new Zend_Navigation();
        $pages = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'admin');
        $navigation->addPages($pages);
        $view->adminNavigation = $navigation;
    }

}

