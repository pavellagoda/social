<?php
// Set the initial include_path. You may need to change this to ensure that
// Zend Framework is in the include_path; additionally, for performance
// reasons, it's best to move this to your web server configuration or php.ini
// for production.

//ini_set('max_upload_filesize', 22485760);
//phpinfo();

define('APPLICATION_ENV', 'development');

error_reporting(E_ALL);
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    realpath(dirname(__FILE__) . '/../application'),
    realpath(dirname(__FILE__) . '/../../zf'),	
    get_include_path(),
)));

/** Sets default time zone */
date_default_timezone_set('UTC');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to library directory
defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));
    
    
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$application->run();
