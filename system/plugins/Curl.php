<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * CURL Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr
 */

class Curl
{

	protected $user_agent 		= 'test';

	protected $timeout 			= 0;

	protected $return_transfer 	= true;

	protected $follow_location 	= false;

	protected $referer 			= null;

	protected $proxy 			= null;

	protected $port 			= null;

	protected $ssl_verifypeer 	= false;

	public $error;

	public $errno;

	private $curl;

	public function __construct($config = [])
	{
		if (!extension_loaded('curl')) {
			$code	= 1010;
			$text	= 'CURL eklentisi yüklü değil.';
			require_once APP_DIR . 'views/errors/error_system.php';
			die();
		}

		$this->config($config);
	}

	/**
	 * Configuration
	 * @param $config
	 * @return void
	 */
	private function config($config = [])
	{
		if (array_key_exists('user_agent', $config))
			$this->user_agent 		= $config['user_agent'];

		if (array_key_exists('timeout', $config))
			$this->timeout 			= $config['timeout'];

		if (array_key_exists('return_transfer', $config))
			$this->return_transfer 	= $config['return_transfer'];

		if (array_key_exists('follow_location', $config))
			$this->follow_location 	= $config['follow_location'];

		if (array_key_exists('referer', $config))
			$this->referer 			= $config['referer'];

		if (array_key_exists('proxy', $config))
			$this->proxy 			= $config['proxy'];

		if (array_key_exists('port', $config))
			$this->port 			= $config['port'];

		if (array_key_exists('ssl_verifypeer', $config))
			$this->ssl_verifypeer 	= $config['ssl_verifypeer'];
	}

	/**
	 * Initializing CURL 
	 * @param $url
	 * @param $proxy
	 * @return void
	 */
	public function init($url, $proxy = null)
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $this->return_transfer);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $this->follow_location);
		curl_setopt($this->curl, CURLOPT_REFERER, $this->referer);

		if (!is_null($this->proxy))
			curl_setopt($this->curl, CURLOPT_PROXY, $this->proxy);

		if ($this->port)
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

	public function __destruct()
	{
		curl_close($this->curl);
	}


}