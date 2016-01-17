<?php defined('DIRECT') OR exit('No direct script access allowed');

class App 
{
	protected $config;
	protected $controller;
	protected $method;
	protected $params = [];
	protected $routes;
	protected $url;
	

	public function __construct()
	{
		// Getting config file
		require_once APP_DIR . 'config/config.php';
		$this->config 		= $config;

		// Setting default controller and method
		$this->controller 	= $this->config['default_controller'];
		$this->method 		= $this->config['default_method'];

		// Getting current URL
		$this->url			= $this->parseURL();

		// Getting routes
		$this->routes 		= $this->loadFile(APP_DIR . 'config/routes');

		// Getting Controller
		$this->set_controller($this->url);

		// Getting Method
		$this->set_action($this->url);

		// Getting parameters
		if(!$this->params)
			$this->set_params($this->url);

		// Composer Autoload
		if($this->config['composer'] == true) {
			if(file_exists('vendor/autoload.php'))
				require_once('vendor/autoload.php');
			else
				echo '<span style="color:#bc5858;"><strong>UYARI:</strong> Composer yükleyicisi bulunamadı.</span>';
		}

		call_user_func_array([$this->controller, $this->method], $this->params);
	}

	/**
	 * Setting Controller
	 * @param 	array $url
	 * @return 	void
	 */
	private function set_controller($url)
	{
		if(isset($url[0])) {
			if (file_exists(APP_DIR . 'controllers/' . $this->makeURL($url[0]) . '.php')) {
				$this->controller = $this->makeURL($url[0]);
				$this->loadFile(APP_DIR . 'controllers/' . $this->controller);
				$this->controller = new $this->controller;
			} else {
				// Check Route Exists
				if (array_key_exists($this->makeURL($url[0]), $this->routes)) {
					$route = explode('/', $this->routes[$this->makeURL($url[0])]);
					$this->controller = $route[0];
					$this->loadFile(APP_DIR . 'controllers/' . $this->controller);
					$this->controller = new $this->controller;
				} else {
					$this->loadFile(APP_DIR . 'views/errors/error_404');
					die();
				}
			}
		} else {
			$this->loadFile(APP_DIR . 'controllers/' . $this->controller);
			$this->controller = new $this->controller;
		}
	}

	/**
	 * Setting Action
	 * @param 	array $url
	 * @return 	void
	 */
	private function set_action($url)
	{
		if (isset($url[1])) {
			if ($url[1] != 'index') {
				if (method_exists($this->controller, $url[1])) {
					$this->method = $url[1];
				} else {
					unset($url[0]);
					if(method_exists($this->controller, $this->method)) {
						$this->params = array_values($url);	
					} else {
						$this->loadFile(APP_DIR . 'views/errors/error_404');
						die();
					}
				}
			}
			unset($this->url[1]);
		} else {
			// Check Routes for method
			if (array_key_exists($this->makeURL($url[0]), $this->routes)) {
				$route = explode('/', $this->routes[$this->makeURL($url[0])]);
				if(isset($route[1])) {
					if (method_exists($this->controller, $route[1])) {
						$this->method = $route[1];
					} else {
						$this->loadFile(APP_DIR . 'views/errors/error_404');
						die();
					}
				}
			}
		}
		unset($this->url[0]);
	}

	/**
	 * Setting Parameters
	 * @param 	array $url
	 * @return 	void
	 */
	private function set_params($url)
	{
		$this->params = $url ? array_values($url) : [];
	}

	/**
	 * Parsing URL
	 * @return array
	 */
	public function parseURL()
	{
		if (isset($_GET['url'])) {
			return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}

	/**
	 * Making URL
	 * @param 	string $queryString
	 * @return 	string
	 */
	public function makeURL($queryString)
    {
        $queryString = ucwords(strtolower(str_replace(['-','_','%20'], [' ',' ',' '], $queryString)));
        $queryString = str_replace(' ', '_', $queryString);
        return $queryString;
    }

    /** 
     * Loading file
     * @param 	string $fileName
     * @return 	void
     */
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