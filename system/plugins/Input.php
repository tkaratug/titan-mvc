<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Input Plugin
 * 
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Input {

	/**
	 * Clear XSS
	 * @param 	string $data
	 * @return 	string
	 */
	public function xss_clean($data)
	{
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
        	// Remove really unwanted tags
        	$old_data = $data;
        	$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
	}

	/**
	 * Clean HTML
	 * @param 	string $data
	 * @return  string
	 */
	public function clean($data)
	{
		return strip_tags(htmlentities(trim(stripslashes($data)), ENT_NOQUOTES, "UTF-8"));
	}

	/**
	 * HTTP GET Request
	 * @param 	string 	$data
	 * @param 	bool 	$xss_clean
	 * @return 	string
	 */
	public function get($data = null, $xss_clean = true)
	{
		if($data == null) {
			return $_GET;
		} else {
			if($xss_clean == true) {
				return $this->xss_clean($_GET[$data]);
			} else {
				return $_GET[$data];
			}
		}
	}

	/**
	 * HTTP POST Request
	 * @param 	string 	$data
	 * @param 	bool 	$xss_clean
	 * @return 	string
	 */
	public function post($data = null, $xss_clean = true) {
		if($data == null) {
			return $_POST;
		} else {
			if($xss_clean == true) {
				return $this->xss_clean($_POST[$data]);
			} else {
				return $_POST[$data];
			}
		}
	}

	/**
	 * HTTP PUT Request
	 * @param 	string 	$data
	 * @param 	bool 	$xss_clean
	 * @return 	string
	 */
	public function put($data = null, $xss_clean = true)
	{
		parse_str(file_get_contents("php://input"),$post_vars);

		if($data == null) {
			return $post_vars;
		} else {
			if($xss_clean == true) {
				return $this->xss_clean($post_vars[$data]);
			} else {
				return $post_vars[$data];
			}
		}
	}

	/**
	 * HTTP DELETE Request
	 * @param 	string 	$data
	 * @param 	bool 	$xss_clean
	 * @return 	string
	 */
	public function delete($data = null, $xss_clean = true)
	{
		parse_str(file_get_contents("php://input"),$post_vars);

		if($data == null) {
			return $post_vars;
		} else {
			if($xss_clean == true) {
				return $this->xss_clean($post_vars[$data]);
			} else {
				return $post_vars[$data];
			}
		}
	}

	/**
	 * HTTP Request Method
	 * @return 	string
	 */
	public function request_method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

}