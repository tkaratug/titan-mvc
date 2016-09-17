<?php
/**
 * TITAN Mini MVC Framework
 * 
 * Titan is a simple mvc application framework for php developers.
 *
 * @author 		Turan Karatuğ - <tkaratug@hotmail.com.tr> - <www.turankaratug.com>
 * @version 	1.1.0
 * @copyright	2016
 * @license		https://opensource.org/licenses/MIT
 * @link 		https://github.com/tkaratug/titan-mvc
 */

// Constants
define('BASE_DIR', '/');
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('SYSTEM_DIR', ROOT_DIR .'system/');
define('APP_DIR', ROOT_DIR .'app/');
define('VERSION', '1.1.0');
define('DIRECT', true);
define('ENVIRONMENT', 'development'); // production | development

// Error Reporting
if(ENVIRONMENT == 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

// General Functions
require_once SYSTEM_DIR . 'core/Functions.php';

// Loading core classes
require_once SYSTEM_DIR . 'core/App.php';
require_once SYSTEM_DIR . 'core/Loader.php';
require_once SYSTEM_DIR . 'core/Controller.php';
require_once SYSTEM_DIR . 'core/Model.php';

// Starting Titan
$app = new App();
