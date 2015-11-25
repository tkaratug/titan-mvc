<?php defined('DIRECT') OR exit('No direct script access allowed');

class App 
{
	protected $controller;
	protected $method;
	protected $params = [];
	protected $routes;
	

	public function __construct()
	{
		global $config;
		$this->controller 	= $config['default_controller'];
		$this->method 		= $config['default_method'];

		$url 			= $this->parseURL();
		$this->routes 	= $this->loadFile(APP_DIR . 'config/routes');

		// Getting Controller
		if(isset($url[0])) {
			if (file_exists(APP_DIR . 'controllers/' . $this->makeURL($url[0]) . '.php')) {
				$this->controller = $this->makeURL($url[0]);
				$this->loadFile(APP_DIR . 'controllers/' . $this->controller);
				$this->controller = new $this->controller;
				unset($url[0]);
			} else {
				// Check Route Exists
				if (array_key_exists($this->makeURL($url[0]), $this->routes)) {
					$this->controller = $this->routes[$this->makeURL($url[0])];
					$this->loadFile(APP_DIR . 'controllers/' . $this->controller);
					$this->controller = new $this->controller;
					unset($url[0]);
				} else {
					$this->loadFile(APP_DIR . 'views/errors/error_404');
					die();
				}
			}
		} else {
			$this->loadFile(APP_DIR . 'controllers/' . $this->controller);
			$this->controller = new $this->controller;
		}
		

		// Getting Method
		if (isset($url[1])) {
			if ($url[1] != 'index') {
				if (method_exists($this->controller, $url[1])) {
					$this->method = $url[1];
					unset($url[1]);
				} else {
					$this->loadFile(APP_DIR . 'views/errors/error_404');
					die();
				}
			}		
		}

		// Check Parameter Exists
		$this->params = $url ? array_values($url) : [];

		call_user_func_array([$this->controller, $this->method], $this->params);

	}

	public function parseURL()
	{
		if (isset($_GET['url'])) {
			return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}

	public function makeURL($queryString)
    {
        $queryString = ucwords(strtolower(str_replace(['-','_','%20'], [' ',' ',' '], $queryString)));
        $queryString = str_replace(' ', '_', $queryString);
        return $queryString;
    }

    public function loadFile($fileName) {
        $fileName 	= $fileName . '.php';
        if (file_exists($fileName)) {
            return require_once $fileName;
        } else {
        	$code 	= 1005;
        	$text 	= 'Böyle bir dosya bulunmamaktadır. {' . $fileName . '}';
        	require_once APP_DIR . 'views/errors/error_system.php';
            die();
        }
    }
}