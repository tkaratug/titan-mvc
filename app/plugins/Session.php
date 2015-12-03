<?php defined('DIRECT') OR exit('No direct script access allowed');

class Session
{
	function __construct()
	{
		self::init();
	}

	/**
	 * Starting Session
	 * @return void
	 */
	public static function init()
	{
		if(!isset($_SESSION))
			session_start();
	}

	/**
	 * Setting a session
	 * @param 	string 	$key
	 * @param 	string 	$value
	 * @return 	string
	 */
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Getting a session
	 * @param 	string 	$key
	 * @return 	string
	 */
	public static function get($key)
	{
		return $_SESSION[$key];
	}

	/**
	 * Is session exists
	 * @param 	string 	$key
	 * @return 	bool
	 */
	public static function is_exists($key)
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * Unset a session
	 * @param 	string 	$key
	 * @return 	void
	 */
	public static function delete($key)
	{
		unset($_SESSION[$key]);
	}

	/**
	 * Destroy a session
	 * @return 	string
	 */
	public static function destroy()
	{
		session_destroy();
	}
}