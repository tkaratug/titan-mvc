<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Cookie Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Cookie
{
	
	protected $config;
	protected $seperator = '##';

	function __construct()
	{
		// Getting config elements
		require APP_DIR . 'config/config.php';
		$this->config = $config;
	}

	/**
	 * Setting Cookie
	 * @param 	string 	$name
	 * @param 	string 	$value
	 * @param 	int 	$time
	 * @return 	bool
	 */	
	public function set($name, $value, $time = null)
	{
		if(is_null($time)) {
			if($this->config['cookie_security'] == true)
				setcookie($name, $value . $this->seperator . md5($value . $this->config['encryption_key']));
			else
				setcookie($name, $value);
		} else {
			if($this->config['cookie_security'] == true)
				setcookie($name, $value . $this->seperator . md5($value . $this->config['encryption_key']), time() + (60*60*$time));
			else
				setcookie($name, $value, time() + (60*60*$time));
		}
	}

	/**
	 * Getting Cookie
	 * @param 	string 	$name
	 * @return 	string
	 */
	public function get($name)
	{
		if($this->has($name)) {
			if($this->config['cookie_security'] == true) {
				$slices = explode($this->seperator, $_COOKIE[$name]);
				if(md5($slices[0] . $this->config['encryption_key']) == $slices[1])
					return $slices[0];
				else
					die('Cookie içeriği değiştirilmiş');
			} else {
				return $_COOKIE[$name];
			}
		}
	}

	/**
	 * Delete Cookie
	 * @param 	string 	$name
	 * @return 	bool
	 */
	public function delete($name)
	{
		if($this->has($name)) {
			unset($_COOKIE[$name]);
			setcookie($name, '', time() - 3600);
		} else {
			return false;
		}
	}

	/**
	 * Is Cookie Exists
	 * @param 	string 	$name
	 * @return 	bool
	 */
	public function has($name)
	{
		if(isset($_COOKIE[$name]))
			return true;
		else
			return false;
	}

}