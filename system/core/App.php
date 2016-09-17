<?php defined('DIRECT') OR exit('No direct script access allowed');

class App 
{
	protected $config;
	protected $controller;
	protected $method;
	protected $params 			= [];
	protected $routes;
	protected $url;
	protected $directory;
	protected $controller_dir 	= false;
	

	public function __construct()
	{
		// Getting config file
		$this->config 		= $this->loadFile(APP_DIR . 'config/config');

		// Setting default controller and method
		$this->controller 	= $this->config['default_controller'];
		$this->method 		= $this->config['default_method'];

		// Setting controller directory
		if ($this->config['default_directory']) {
			$this->directory = 'controllers/' . $this->config['default_directory'] . '/';
		} else {
			$this->directory = 'controllers/';
		}

		// Getting current URL
		$this->url			= $this->parseURL();

		// Getting routes
		$this->routes 		= $this->loadFile(APP_DIR . 'config/routes');

		if($this->run() === false) {
			// Getting Controller
			$this->set_controller($this->url);

			// Getting Method
			$this->set_action($this->url);

			// Getting parameters
			$this->set_params($this->url);
		}

		// Composer Autoload
		if($this->config['composer'] == true) {
			if(file_exists('vendor/autoload.php'))
				require_once('vendor/autoload.php');
			else
				echo '<span style="color:#bc5858;"><strong>UYARI:</strong> Composer yükleyicisi bulunamadı.</span>';
		}

		call_user_func_array([$this->controller, $this->method], $this->params);
	}

	private function run()
	{
		$matched = 0;
        $url = ltrim(str_replace(BASE_DIR,'',$_SERVER['REQUEST_URI']), '/');

        foreach($this->routes as $key => $value) {
            $key = '/^' . str_replace('/', '\/', $key) . '$/';

            if(preg_match($key, $url)) {
                $matched++;
                preg_match_all($key, $url, $matches);
                array_shift($matches);
                $target = explode('/', $value);

                if (is_dir(APP_DIR . 'controllers/' . $target[0])) {
                	if (file_exists(APP_DIR . 'controllers/' . $target[0] . '/' . ucfirst($target[1]) . '.php')) {
                		$this->controller = $this->makeURL(ucfirst($target[1]));
                		$this->loadFile(APP_DIR . 'controllers/' . $target[0] . '/' . $this->controller);
                		$this->controller = new $this->controller;
                	} else {
                		$this->loadFile(APP_DIR . 'views/errors/error404');
                		die();
                	}

                	$this->method = $target[2];
                	array_diff_key($target, array_flip(array(0,1,2)));
                } else {
                	if(file_exists(APP_DIR . 'controllers/' . ucfirst($target[0]) . '.php')) {
	                    $this->controller = $this->makeURL(ucfirst($target[0]));
	                    $this->loadFile(APP_DIR . 'controllers/' . $this->controller);
	                    $this->controller = new $this->controller;
	                } else {
	                    $this->loadFile(APP_DIR . 'views/errors/error_404');
						die();
	                }

	                $this->method = $target[1];
                	array_diff_key($target, array_flip(array(0,1)));
                }                

                foreach($matches as $indis => $match) {
                    $target[$indis] = $match[0];
                }

                $this->params = $target ? array_values($target) : [];
            }
        }

        if($matched > 0)
            return true;
        else
            return false;
	}

	/**
	 * Setting Controller
	 * @param 	array $url
	 * @return 	void
	 */
	private function set_controller($url)
	{
		if(isset($url[0])) {

			if (is_dir(APP_DIR . 'controllers/' . $this->makeURL($url[0]))) {
				$this->controller_dir = true;
				if (file_exists(APP_DIR . 'controllers/' . $this->makeURL($url[0]) . '/' . $this->makeURL($url[1]) . '.php')) {
					$this->controller = $this->makeURL($url[1]);
					$this->loadFile(APP_DIR . 'controllers/' . $this->makeURL($url[0]) . '/' . $this->controller);
					$this->controller = new $this->controller;
				} else {
					$this->loadFile(APP_DIR . 'views/errors/error_404');
					die();
				}
			} else {
				if (file_exists(APP_DIR . $this->directory . $this->makeURL($url[0]) . '.php')) {
					$this->controller = $this->makeURL($url[0]);
					$this->loadFile(APP_DIR . $this->directory . $this->controller);
					$this->controller = new $this->controller;
				} else {
					$this->loadFile(APP_DIR . 'views/errors/error_404');
					die();
				}
			}

			array_diff_key($url, [0,1]);
		} else {
			$this->loadFile(APP_DIR . $this->directory . $this->controller);
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
		if ($this->controller_dir === true) {
			if (isset($url[2])) {
				if ($url[2] != 'index') {
					if (method_exists($this->controller, $url[2])) {
						$this->method = $url[2];
					} else {
						$this->loadFile(APP_DIR . 'views/errors/error_404');
						die();
					}
				}
				unset($this->url[2]);
			}
		} else {
			if (isset($url[1])) {
				if ($url[1] != 'index') {
					if (method_exists($this->controller, $url[1])) {
						$this->method = $url[1];
					} else {
						$this->loadFile(APP_DIR . 'views/errors/error_404');
						die();
					}
				}
				unset($this->url[1]);
			}
		}
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