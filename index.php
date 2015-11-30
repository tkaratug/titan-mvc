<?php

// Constants
define('BASE_DIR', 'http://siteadi.com/');
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'app/');
define('VERSION', '1.0.1');
define('DIRECT', true);
define('ENVIRONMENT', 'production'); // production | development

// Autoload Core Classes
function autoload_core($class_name) {
    require_once APP_DIR . 'core/' . $class_name . '.php';
}

spl_autoload_register('autoload_core');

// Starting Titan
$app = new App();
