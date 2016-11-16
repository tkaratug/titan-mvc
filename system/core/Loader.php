<?php defined('DIRECT') OR exit('No direct script access allowed');

class Loader
{

	private static $instance;
	
	protected $loaded_configs = [];

	public function __construct() {
        self::$instance = $this;
    }

    /**
     * Getting Instance
     * @return object
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	/**
	 * Loading Model
	 * @param 	string $model
	 * @return 	object
	 */
	public function model($model, $custom_name = null)
	{
		if (file_exists(APP_DIR . 'models/' . ucfirst($model) . '.php')) {
			require_once APP_DIR . 'models/' . ucfirst($model) . '.php';
			if(is_null($custom_name))
				$this->$model = new $model();
			else
				$this->$custom_name = new $model();
		} else {
			$code	= 1001;
			$text	= 'Model bulunamadı';
			require_once APP_DIR . 'views/errors/error_system.php';
			die();
		}
	}

	/**
	 * Loading View
	 * @param 	string 	$view
	 * @param 	array 	$data
	 * @return 	void
	 */
	public function view($view, $data = [])
	{
		if (file_exists(APP_DIR . 'views/' . $view . '.php')) {
			extract($data);
			require_once APP_DIR . 'views/' . $view . '.php';
		} else {
			$code	= 1002;
			$text	= 'View bulunamadı';
			require_once APP_DIR . 'views/errors/error_system.php';
			die();
		}
	}

	/**
	 * Loading Plugin
	 * @param 	string $plugin
	 * @return 	object
	 */
	public function plugin($plugin, $params = null)
	{
		if(file_exists(APP_DIR . 'plugins/' . ucfirst($plugin) . '/' . ucfirst($plugin) . '.php') || file_exists(APP_DIR . 'plugins/' . ucfirst($plugin) . '.php')) {
			if(file_exists(APP_DIR . 'plugins/' . ucfirst($plugin) . '/' . ucfirst($plugin) . '.php')) {
				require_once APP_DIR . 'plugins/' . ucfirst($plugin) . '/' . ucfirst($plugin) . '.php';
				if(is_null($params))
					$this->$plugin = new $plugin;
				else
					$this->$plugin = new $plugin($params);
			} else {
				if (file_exists(APP_DIR . 'plugins/' . ucfirst($plugin) . '.php')) {
					require_once APP_DIR . 'plugins/' . ucfirst($plugin) . '.php';
					if(is_null($params))
						$this->$plugin = new $plugin;
					else
						$this->$plugin = new $plugin($params);
				}
			}
		} elseif(file_exists(SYSTEM_DIR . 'plugins/' . ucfirst($plugin) . '/' . ucfirst($plugin) . '.php') || file_exists(SYSTEM_DIR . 'plugins/' . ucfirst($plugin) . '.php')) {
			if(file_exists(SYSTEM_DIR . 'plugins/' . ucfirst($plugin) . '/' . ucfirst($plugin) . '.php')) {
				require_once SYSTEM_DIR . 'plugins/' . ucfirst($plugin) . '/' . ucfirst($plugin) . '.php';
				if(is_null($params))
					$this->$plugin = new $plugin;
				else
					$this->$plugin = new $plugin($params);
			} else {
				if (file_exists(SYSTEM_DIR . 'plugins/' . ucfirst($plugin) . '.php')) {
					require_once SYSTEM_DIR . 'plugins/' . ucfirst($plugin) . '.php';
					if(is_null($params))
						$this->$plugin = new $plugin;
					else
						$this->$plugin = new $plugin($params);
				}
			}
		} else {
			$code = 1003;
			$text = 'Plugin bulunamadı';
			require_once APP_DIR . 'views/errors/error_system.php';
			die();
		}
	}

	/**
	 * Loading Helper
	 * @param 	string $helper
	 * @return 	void
	 */
	public function helper($helper)
	{
		if (file_exists(APP_DIR . 'helpers/' . ucfirst($helper) . '.php')) {
			require_once APP_DIR . 'helpers/' . ucfirst($helper) . '.php';
		} elseif(file_exists(SYSTEM_DIR . 'helpers/' . ucfirst($helper) . '.php')) {
			require_once SYSTEM_DIR . 'helpers/' . ucfirst($helper) . '.php';
		} else {
			$code	= 1004;
			$text	= 'Helper bulunamadı';
			require_once APP_DIR . 'views/errors/error_system.php';
			die();
		}
	}

	/**
	 * Config Loader
	 * @param 	string $configFile
	 * @param 	string $env ('prod' or 'dev')
	 * @return 	void
	 */
	public function config($config, $env = 'prod')
	{
		if($env == 'prod')
			$full_path = APP_DIR . 'config/' . $config . '.php';
		elseif($env == 'dev')
			$full_path = APP_DIR . 'config/development/' . $config . '.php';

		if(file_exists($full_path)) {
			if(!array_key_exists($config, $this->loaded_configs)) {
				$this->loaded_configs[$config] = require_once $full_path;
			return $this->loaded_configs[$config];
		} else {
			$code 	= 1008;
        	$text 	= 'Config dosyası bulunamadı. {' . $config . '}';
        	require_once APP_DIR . 'views/errors/error_system.php';
            die();
		}
	}

	/**
	 * Database Plugin Loader
	 * @return object
	 */
	public function database()
	{
		require_once SYSTEM_DIR . 'plugins/Database.php';

		if(ENVIRONMENT != 'production')
            return Database::init($this->config('db', 'dev'));
        else
            return Database::init($this->config('db'));
	}

	/**
	 * Hook Loader
	 * @param 	string $hook
	 * @return 	void
	 */
	public function hook($hook)
	{
		$hook_file = APP_DIR . 'hooks/' . ucfirst($hook) . '.php';

		if(file_exists($hook_file)) {
			require_once $hook_file;
		} else {
			$code 	= 1009;
        	$text 	= 'Hook dosyası bulunamadı. {' . $hook . '}';
        	require_once APP_DIR . 'views/errors/error_system.php';
            die();
		}
	}

}

?>