<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * URL Helper
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

/**
 * Getting Request Scheme
 * @return string
 */
if ( ! function_exists('request_scheme')) {
	function request_scheme()
	{
		if (isset($_SERVER['HTTPS']))
			return 'https://';
		else
			return 'http://';
	}
}

/**
 * Getting Http Host
 * @return string
 */
if ( ! function_exists('http_host')) {
	function http_host()
	{
		return $_SERVER['HTTP_HOST'];
	}
}

/**
 * Getting Request URI
 * @return string
 */
if ( ! function_exists('request_uri')) {
	function request_uri()
	{
		return $_SERVER['REQUEST_URI'];
	}
}

/**
 * Getting Full Path of application
 * @return string
 */
if ( ! function_exists('full_path')) {
	function full_path()
	{
		return request_scheme() . http_host() . request_uri();
	}
}

/**
 * Getting Current Url
 * @return string
 */
if ( ! function_exists('current_url')) {
	function current_url()
	{
		return request_scheme() . http_host() . request_uri();
	}
}

/**
 * Getting base url of application
 * @return string
 */
if ( ! function_exists('base_url')) {
	function base_url($path = null)
	{
		if(is_null($path)) {
			if(BASE_DIR == '/')
				return request_scheme() . http_host();
			else
				return request_scheme() . http_host() . BASE_DIR;
		} else {
			if(BASE_DIR == '/')
				return request_scheme() . http_host() . '/' . $path;
			else
				return request_scheme() . http_host() . BASE_DIR . '/' . $path;
		}
	}
}

/**
 * Getting URL segments
 * @param 	int $index
 * @return 	array | string
 */
if ( ! function_exists('get_segments')) {
	function get_segments($index = null)
	{
		$segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
		if(is_null($index)) {
			return $segments;
		} else {
			if(array_key_exists($index, $segments))
				return $segments[$index];
			else
				return '<span style="color:#bc5858"><strong>UYARI:</strong> Böyle bir segment bulunmamaktadır - get_segments(' . $index . ')</span>';
		}
	}
}

/**
 * Getting current segment
 * @return string
 */
if ( ! function_exists('current_segment')) {
	function current_segment()
	{
		return get_segments( count(get_segments()) - 1 );
	}
}

/**
 * Getting image file
 * @param 	string 	$filepath
 * @return 	string
 */
if ( ! function_exists('get_image')) {
	function get_image($filepath)
	{
		return base_url('public/images/' . $filepath);
	}
}

/**
 * Getting CSS Style File
 * @param 	string 	$filepath
 * @return 	string
 */
if ( ! function_exists('get_css')) {
	function get_css($filepath)
	{
		return base_url('public/css/' . $filepath);
	}
}

/**
 * Getting JS File
 * @param 	string 	$filepath
 * @return 	string
 */
if ( ! function_exists('get_js')) {
	function get_js($filepath)
	{
		return base_url('public/js/' . $filepath);
	}
}

/**
 * Redirection
 * @param 	string 	$link
 * @return 	void
 */
if ( ! function_exists('redirect')) {
	function redirect($link, $method = 'location')
	{
		if($method == 'refresh')
			header("Refresh:0;url=" . $link);
		else
			header("Location:" . $link);
	}
}

/**
 * Converting urls to clickable hyperlink in text
 * @param 	string $text
 * @return 	string
 */
if ( ! function_exists('make_link')) {
	function make_link($text)
	{
		$pattern 	= '/(((http[s]?:\/\/(.+(:.+)?@)?)|(www\.))[a-z0-9](([-a-z0-9]+\.)*\.[a-z]{2,})?\/?[a-z0-9.,_\/~#&=:;%+!?-]+)/is';
		$text 		= preg_replace($pattern, ' <a href="$1">$1</a>', $text);
		$text 		= preg_replace('/href="www/', 'href="http://www', $text);
		return $text;
	}
}

/**
 * Converting email adressess to clickable mailto in text
 * @param 	string $text
 * @return 	string
 */
if ( ! function_exists('make_mailto')) {
	function make_mailto($text)
	{
	    $regex 		= '/(\S+@\S+\.\S+)/';
	    $replace 	= '<a href="mailto:$1">$1</a>';
	    return preg_replace($regex, $replace, $text);
	}
}

/**
 * Safe mail
 * @param 	string $email
 * @return 	string
 */
if ( ! function_exists('safe_mail')) {
	function safe_mail($email)
	{ 
		$character_set 	= '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
		$key 			= str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);

		for($i=0;$i<strlen($email);$i+=1) {
			$cipher_text.= $key[strpos($character_set,$email[$i])];
		}

	  	$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
	  	$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
	  	$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';

	  	$script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
	  	$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';

	  	return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
	}
}