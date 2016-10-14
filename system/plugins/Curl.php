<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * CURL Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr
 */

class Curl
{

	protected $user_agent 		= $_SERVER['HTTP_USER_AGENT'];

	protected $timeout 			= 0;

	protected $return_transfer 	= true;

	protected $follow_action 	= false;

	protected $referer 			= null;

	protected $proxy 			= null;

	protected $port 			= null;

	protected $ssl_verifypeer 	= false;

	public $error;

	public $errno;

	private $curl;

	public function __construct()
	{
		if (!extension_loaded('curl')) {
			$code	= 1010;
			$text	= 'CURL eklentisi yüklü değil.';
			require_once APP_DIR . 'views/errors/error_system.php';
			die();
		}
	}

	/**
	 * Initializing CURL 
	 * @param $url
	 * @param $proxy
	 * @return void
	 */
	public function init($url, $proxy)
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $this->return_transfer);
		curl_setopt($this->curl, CURLOPT_FOLLOWACTION, $this->follow_action);
		curl_setopt($this->curl, CURLOPT_REFERER, $this->referer);

		if ($this->proxy)
			curl_setopt($this->curl, CURLOPT_PROXY, $this->proxy);

		if($this->port)
			curl_setopt($this->curl, CURLOPT_PROXYPORT, $this->port);

		$this->errno = curl_errno($this->curl);
		$this->error = curl_error($this->curl);
	}

	/**
	 * Get Request
	 * @param $url
	 * @param $proxy
	 * @return string
	 */
	public function get($url, $proxy = null)
	{
		$this->init($url, $proxy);
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($this->curl);
		return $result;
	}

	/**
	 * Post Request
	 * @param $url
	 * @param $data
	 * @param $proxy
	 * @return string
	 */
	public function post($url, $data, $proxy = null)
	{
		$this->init($url, $proxy);
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($this->curl, CURLOPT_FAILONERROR, true);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));

		$result = curl_exec($this->curl);
		return $result;
	}

	/**
	 * Put Request
	 * @param $url
	 * @param $data
	 * @param $proxy
	 * @return string
	 */
	public function put($url, $data, $proxy = null)
	{
		$this->init($url, $proxy);
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($this->curl, CURLOPT_FAILONERROR, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($this->curl);
		return $result;
	}

	/**
	 * Delete Request
	 * @param $url
	 * @param $data
	 * @param $proxy
	 * @return string
	 */
	public function delete($url, $data, $proxy = null)
	{
		$this->init($url, $proxy);
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($this->curl, CURLOPT_FAILONERROR, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($this->curl);
		return $result;
	}

	public __destruct()
	{
		curl_close($this->curl);
	}


}