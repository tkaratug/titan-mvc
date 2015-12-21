<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Cookie Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Cookie
{
	
	protected static $config;
	protected static $seperator = '##';

	function __construct()
	{
		// Getting config elements
		require APP_DIR . 'config/config.php';
		self::$config = $config;
	}

	/**
	 * Setting Cookie
	 * @param 	string 	$name
	 * @param 	string 	$value
	 * @param 	int 	$time
	 * @return 	bool
	 */	
	public static function set($name, $value, $time = null)
	{
		if(is_null($time)) {
			if(self::$config['cookie_security'] == true)
				setcookie($name, $value . self::$seperator . md5($value . self::$config['encryption_key']));
			else
				setcookie($name, $value);
		} else {
			if(self::$config['cookie_security'] == true)
				setcookie($name, $value . self::$seperator . md5($value . self::$config['encryption_key']), time() + (60*60*$time));
			else
				setcookie($name, $value, time() + (60*60*$time));
		}
	}

	/**
	 * Getting Cookie
	 * @param 	string 	$name
	 * @return 	string
	 */
	public static function get($name)
	{
		if(self::has($name)) {
			if(self::$config['cookie_security'] == true) {
				$slices = explode(self::$seperator, $_COOKIE[$name]);
				if(md5($slices[0] . self::$config['encryption_key']) == $slices[1])
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
	public static function delete($name)
	{
		if(self::has($name))
			self::set($name, '', time() - 3600);
		else
			return false;
	}

	/**
	 * Is Cookie Exists
	 * @param 	string 	$name
	 * @return 	bool
	 */
	public static function has($name)
	{
		if(isset($_COOKIE[$name]))
			return true;
		else
			return false;
	}

}