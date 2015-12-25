<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * User Agent Helper
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

/**
 * Getting user agent
 * @return string
 */
if ( ! function_exists('get_user_agent')) {
	function get_user_agent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}
}

/**
 * Getting real ip address of visitor
 * @return string
 */
if ( ! function_exists('get_ip')) {
	function get_ip()
	{
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

/**
 * Is mobile
 * @return bool
 */
if( ! function_exists('is_mobile')) {
	function is_mobile()
	{
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
}

/**
 * Is referral
 * @return bool
 */
if( ! function_exists('is_referral')) {
	function is_referral()
	{
		if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '')
			return false;
		else
			return true;
	}
}

/**
 * Is robot
 * @return bool
 */
if( ! function_exists('is_robot')) {
	function is_robot()
	{
		if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT']))
			return true;
		else
			return false;
	}
}

/**
* Get the referrer
* @return string
*/
if( ! function_exists('get_referrer')) {
	function get_referrer()
	{
		return (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
	}
}

/**
 * Get acceptable languages
 * @return array
 */
if ( ! function_exists('get_langs')) {
	function get_langs()
	{
		return explode(',', preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE']))));
	}
}

/**
 * Get browser language
 * @return string
 */
if( ! function_exists('get_browser_lang')) {
	function get_browser_lang()
	{
		return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	}
}


?>