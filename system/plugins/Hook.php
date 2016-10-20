<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Hook Plugin
 * 
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Hook
{
	private $titan;
	private $hook;

	private $class 	= null;
	private $method = null;
	private $params = [];

	public function __construct()
	{
		$this->titan 	= Loader::getInstance();
		$this->hook 	= $this->titan->config('hooks');
	}

	/**
	 * Run Hook File
	 * @param $name
	 * @return void
	 */
	public function run($name)
	{
		if (isset($this->hook[$name]) && is_array($this->hook[$name])) {

			$this->titan->hook($this->hook[$name]['filename']);

			if (array_key_exists('class', $this->hook[$name])) {
				$this->class 	= $this->hook[$name]['class'];
			}

			if (array_key_exists('method', $this->hook[$name])) {
				$this->method 	= $this->hook[$name]['method'];
			}

			if (array_key_exists('params', $this->hook[$name])) {
				foreach($this->hook[$name]['params'] as $key => $val) {
					$this->params[$key] = $val;
				}
			}

			if (!is_null($this->class) && !is_null($this->method)) {
				call_user_func_array([$this->class, $this->method], $this->params);
			} elseif (!is_null($this->method)) {
				call_user_func_array($this->method, $this->params);
			}

			$this->reset();

		}
	}

	/**
	 * Add Param to Hook
	 * @param $params
	 * @return void
	 */
	public function add_param($params = [])
	{
		foreach($params as $key => $val) {
			$this->params[$key] = $val;
		}
	}

	/**
	 * Reset Hook
	 * @return void
	 */
	private function reset()
	{
		$this->class 	= null;
		$this->method 	= null;
		$this->params 	= [];
	}
}