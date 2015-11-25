<?php defined('DIRECT') OR exit('No direct script access allowed');

class Load
{
	public function model($model)
	{
		if (file_exists('app/models/' . ucfirst($model) . '.php')) {
			require_once 'app/models/' . ucfirst($model) . '.php';
			return new $model();
		} else {
			$code	= 1001;
			$text	= 'Model bulunamadı';
			require_once 'app/views/errors/error_system.php';
			die();
		}
	}

	public function view($view, $data = [])
	{
		if (file_exists('app/views/' . $view . '.php')) {
			extract($data);
			require_once 'app/views/' . $view . '.php';
		} else {
			$code	= 1002;
			$text	= 'View bulunamadı';
			require_once 'app/views/errors/error_system.php';
			die();
		}
	}

	public function plugin($plugin)
	{
		if (file_exists('app/plugins/' . $plugin . '.php')) {
			require_once 'app/plugins/' . $plugin . '.php';
			return new $plugin();
		} else {
			$code	= 1003;
			$text	= 'Plugin bulunamadı';
			require_once 'app/views/errors/error_system.php';
			die();
		}
	}

	public function helper($helper)
	{
		if (file_exists('app/helpers/' . $helper . '.php')) {
			require_once 'app/helpers/' . $helper . '.php';
		} else {
			$code	= 1004;
			$text	= 'Helper bulunamadı';
			require_once 'app/views/errors/error_system.php';
			die();
		}
	}

}