<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Session Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Session
{

	protected $config;

	function __construct()
	{
		// Configurations for security
		ini_set('session.cookie_httponly', 1);
		ini_set('session.use_only_cookies', 1);

		// Getting config elements
		require APP_DIR . 'config/config.php';
		$this->config = $config;

		// Initialize Session
		$this->init();
	}

	/**
	 * Starting Session and Hijacking Security
	 * @return void
	 */
	public function init()
	{
		if(!isset($_SESSION)) {
			session_start();
			$this->set('session_hash', $this->generate_hash());
		} else {
			if($this->get('session_hash') != $this->generate_hash())
				$this->destroy();
		}
	}

	/**
	 * Setting a session
	 * @param 	string 	$key
	 * @param 	string 	$value
	 * @return 	string
	 */
	public function set($key, $value = null)
	{
		if(is_array($key)) {
			foreach($key as $anahtar => $deger) {
				$_SESSION[$anahtar] = $deger;
			}
		} else {
			$_SESSION[$key] = $value;	
		}
	}

	/**
	 * Getting a session
	 * @param 	string 	$key
	 * @return 	string
	 */
	public function get($key = null)
	{
		if(is_null($key))
			return $_SESSION;
		else
			return $_SESSION[$key];
	}

	/**
	 * Is session exists
	 * @param 	string 	$key
	 * @return 	bool
	 */
	public function is_exists($key)
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * Unset a session
	 * @param 	string 	$key
	 * @return 	void
	 */
	public function delete($key)
	{
		unset($_SESSION[$key]);
	}

	/**
	 * Destroy a session
	 * @return 	string
	 */
	public function destroy()
	{
		session_destroy();
	}

	/**
	 * Generating Hash for Hijacking Security
	 * @return string
	 */
	private function generate_hash()
	{
		return md5(sha1(md5($_SERVER['REMOTE_ADDR'] . $this->config['encryption_key'] . $_SERVER['HTTP_USER_AGENT'])));
	}

	/**
	 * Setting Flash Message
	 * @param 	string $message
	 * @param 	string $url
	 * @return 	bool
	 */
	public function set_flash($message, $redirect_url = null)
	{
		$this->set('flash_message', $message);
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
	public function get_flash()
	{
		$message = $this->get('flash_message');

		$this->delete('flash_message');

		return $message;
	}

	/**
	 * Is Flash Message Exists?
	 * @return bool
	 */
	public function flash_exists()
	{
		return $this->is_exists('flash_message');
	}
}