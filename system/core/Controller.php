<?php defined('DIRECT') OR exit('No direct script access allowed');

class Controller extends Loader
{
	protected $load;
	protected $autoload;

	public function __construct()
	{
		// Getting config elements
		require APP_DIR . 'config/autoload.php';
		$this->autoload = $autoload;

		// Instance of Loader
		$this->load = $this;

		// Autoload Helpers and Plugins
		$this->autoload_helpers();
		$this->autoload_plugins();
	}

	/**
	 * Helper autoloader
	 * @return void
	 */
	public function autoload_helpers()
	{
		if(count($this->autoload['helpers']) > 0) {
			foreach($this->autoload['helpers'] as $helper) {
				$helper_name = ucfirst($helper);
				$this->load->helper($helper_name);
			}
		}
	}

	/**
	 * Plugin autoloader
	 * @return void
	 */
	public function autoload_plugins()
	{
		if(count($this->autoload['plugins']) > 0) {
			foreach($this->autoload['plugins'] as $plugin) {
				$plugin_name = ucfirst($plugin);
				$this->load->plugin($plugin_name);
				$this->$plugin = new $plugin;
			}
		}
	}

}
