<?php defined('DIRECT') OR exit('No direct script access allowed');

class Loader
{
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
}
