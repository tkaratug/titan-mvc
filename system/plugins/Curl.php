<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Curl Plugin 
 *
 * Turan Karatuğ - <tkaratug@hotmail.comtr>
 */

class Curl
{
	// Curl Handle
	private $ch                = null;

	// Folloe Redirects
	public $followRedirects    = true;

	// CURLOPT Options to send with request
	public $options            = [];

	// Headers to send with request
	public $headers            = [];

	// Referer Url
	public $referer            = null;

    // Use Cookie
    public $useCookie          = false;

	// Cookie File
	public $cookieFile         = '';

    // User Agent
    public $userAgent          = '';

	// Response Body
	public $responseBody       = '';

	// Response Headers
	public $responseHeader     = [];

	// Error Message
	private $error             = '';

	function __construct()
	{
        if ($this->useCookie)
            $this->cookieFile  = dirname(__FILE__).DIRECTORY_SEPARATOR.'curl_cookie.txt';

        if ($this->userAgent === '')
            $this->userAgent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'TitanMVC/PHP '.PHP_VERSION.' (http://github.com/tkaratug/titan-mvc)';
	}

	/**
	 * Get Just Header of The Page
	 * @param $url
	 * @param $params
	 * @return void
	 */
	public function head($url, $params = [])
	{
        $this->request('HEAD', $url, $params);
    }

    /**
     * Get Request
     * @param $url
     * @param $params
     * @return void
     */
	public function get($url, $params = [])
	{
        if (!empty($params)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= (is_string($params)) ? $params : http_build_query($params, '', '&');
        }
        $this->request('GET', $url);
    }

    /**
     * Post Request
     * @param $url
     * @param $params
     * @return void
     */
    public function post($url, $params = [])
    {
        $this->request('POST', $url, $params);
    }

    /**
     * Put Request
     * @param $url
     * @param $params
     * @return void
     */
    public function put($url, $params = [])
    {
        $this->request('PUT', $url, $params);
    }

    /**
     * Delete Request
     * @param $url
     * @param $params
     * @return void
     */
	public function delete($url, $params = [])
	{
        $this->request('DELETE', $url, $params);
    }

    /**
     * Make Request
     * @param $method
     * @param $url
     * @param $params
     * @return void
     */
    private function request($method, $url, $params = [])
    {
        $this->error 	= '';
        $this->ch 		= curl_init();
        if (is_array($params)) $params = http_build_query($params, '', '&');
        
        $this->set_request_method($method);
        $this->set_request_options($url, $params);
        $this->set_request_headers();
        
        $response = curl_exec($this->ch);
        
        if ($response) {
            $response = $this->getResponse($response);
        } else {
            $this->error = curl_errno($this->ch).' - '.curl_error($this->ch);
        }
        
        curl_close($this->ch);
    }

    /**
     * Set Request Headers
     */
    private function set_request_headers()
    {
        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = $key.': '.$value;
        }
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * Set Request Method
     * @param $method
     */
    private function set_request_method($method)
    {
        switch (strtoupper($method)) {
            case 'HEAD':
                curl_setopt($this->ch, CURLOPT_NOBODY, true);
                break;
            case 'GET':
                curl_setopt($this->ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($this->ch, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
        }
    }

    /**
     * Set Request Options
     * @param $url
     * @param $params
     * @return mixed
     */
    private function set_request_options($url, $params)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        if (!empty($params)) curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params);
        
        # Set some default CURL options
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);
        if ($this->useCookie !== false) {
            curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookieFile);
            curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        }
        if ($this->followRedirects) curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        if ($this->referer !== null) curl_setopt($this->ch, CURLOPT_REFERER, $this->referer);
        
        # Set any custom CURL options
        foreach ($this->options as $option => $value) {
            curl_setopt($this->ch, constant('CURLOPT_'.str_replace('CURLOPT_', '', strtoupper($option))), $value);
        }
    }

    /**
     * Get Response of the Curl Request
     * @param $response
     */
    private function getResponse($response)
    {
    	# Headers regex
        $pattern = '#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims';

        # Extract headers from response
        preg_match_all($pattern, $response, $matches);
        $headers_string 	= array_pop($matches[0]);
        $headers 			= explode("\r\n", str_replace("\r\n\r\n", '', $headers_string));

        # Remove headers from the response body
        $this->responseBody	= str_replace($headers_string, '', $response);

        # Extract the version and status from the first header
        $version_and_status = array_shift($headers);
        preg_match('#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#', $version_and_status, $matches);
        $this->responseHeader['Http-Version'] 	= $matches[1];
        $this->responseHeader['Status-Code'] 	= $matches[2];
        $this->responseHeader['Status'] 		= $matches[2].' '.$matches[3];

        # Convert headers into an associative array
        foreach ($headers as $header) {
            preg_match('#(.*?)\:\s(.*)#', $header, $matches);
            $this->responseHeader[$matches[1]] = $matches[2];
        }
    }

    /**
     * Get Error
     * @return string
     */
    function getError()
    {
        return $this->error;
    }

}