<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Session Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Session
{

	protected static $config;

	function __construct()
	{
		// Getting config elements
		require APP_DIR . 'config/config.php';
		self::$config = $config;

		// Initialize Session
		self::init();
	}

	/**
	 * Starting Session and Hijacking Security
	 * @return void
	 */
	public static function init()
	{
		if(!isset($_SESSION)) {
			session_start();
			self::set('session_hash', self::generate_hash());
		} else {
			if(self::get('session_hash') != self::generate_hash())
				self::destroy();
		}
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

	/**
	 * Generating Hash for Hijacking Security
	 * @return string
	 */
	private static function generate_hash()
	{
		return md5(sha1(md5($_SERVER['REMOTE_ADDR'] . self::$config['encryption_key'] . $_SERVER['HTTP_USER_AGENT'])));
	}

	/**
	 * Setting Flash Message
	 * @param 	string $message
	 * @param 	string $url
	 * @return 	bool
	 */
	public static function set_flash($message, $redirect_url = null)
	{
		self::set('flash_message', $message);
		if (!is_null($redirect_url)) {
			header("Location: $redirect_url");
			exit();
		}
		return true;
	}

	/**
	 * Getting Flash Message
	 * @return string
	 */
	public static function get_flash()
	{
		$message = self::get('flash_message');

		self::delete('flash_message');

		return $message;
	}

	/**
	 * Is Flash Message Exists?
	 * @return bool
	 */
	public static function flash_exists()
	{
		return self::is_exists('flash_message');
	}
}