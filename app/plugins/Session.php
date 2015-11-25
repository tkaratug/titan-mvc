<?php defined('DIRECT') OR exit('No direct script access allowed');

class Session
{
	function __construct()
	{
		$this->init();
	}

	public static function init()
	{
		session_start();
	}

	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public static function get($key)
	{
		return $_SESSION[$key];
	}

	public static function is_exists($key)
	{
		return isset($_SESSION[$key]);
	}

	public static function delete($key)
	{
		unset($_SESSION[$key]);
	}

	public static function destroy()
	{
		session_destroy();
	}
}