<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Response Plugin
 * 
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Response
{

	public $status_codes = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	];

	/**
	 * Setting Header
	 * @param 	string $key
	 * @param 	string $value
	 * @return 	bool
	 */
	public function set_header($key, $value)
	{
		if(headers_sent())
			return false;

		header($key . ': ' . $value);

		return true;
	}

	/**
	 * Getting Header
	 * @param 	string $key
	 * @return 	string
	 */
	public function get_header($key)
	{
		$default_headers 	= getallheaders();
		$custom_headers 	= headers_list();

		if($this->array_search_like($key, $custom_headers) !== false) {
			$index 	= $this->array_search_like($key, $custom_headers);
			$header = explode(':', $custom_headers[$index]);
			return trim($header[1]);
		} elseif(array_key_exists($key, $default_headers)) {
			return $default_headers[$key];
		} else {
			return 'Header bilgisi bulunamadı';
		}
	}

	/**
	 * Make array like search
	 * @param  	string 	$element
	 * @param 	array 	$array
	 * @return 	int|bool
	 */
	private function array_search_like($element, $array)
	{
		foreach($array as $key => $value) {
			if(strpos($value, $element) !== false)
				return $key;
		}
		return false;
	}

	/**
	 * Setting Header Status
	 * @param 	int $code
	 * @return 	bool
	 */
	public function set_status($code)
	{
		return http_response_code($code);
	}

	/**
	 * Getting Header Status
	 * @return 	array
	 */
	public function get_status($code = null)
	{
		if(is_null($code))
			$status_code = http_response_code();
		else
			$status_code = $code;

		return [
			'code'	=> $status_code,
			'text'	=> $this->status_codes[$status_code]
		];
	}

}