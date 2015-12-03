<?php
/**
 * TITAN Mini MVC Framework
 * 
 * Titan is a simple mvc application framework for php developers.
 *
 * @author 		Turan Karatuğ - <tkaratug@hotmail.com.tr> - <www.turankaratug.com>
 * @version 	1.0.2
 * @copyright	2015
 * @license		https://opensource.org/licenses/MIT
 * @link 		https://github.com/tkaratug/titan-mvc
 */

// Constants
define('BASE_DIR', '/');
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'app/');
define('VERSION', '1.0.2');
define('DIRECT', true);
define('ENVIRONMENT', 'production'); // production | development

// Autoload Core Classes
function autoload_core($class_name) {
    require_once APP_DIR . 'core/' . $class_name . '.php';
}

spl_autoload_register('autoload_core');

// Starting Titan
$app = new App();