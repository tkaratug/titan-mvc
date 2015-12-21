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
function request_scheme()
{
	return $_SERVER['REQUEST_SCHEME'] . '://';
}

/**
 * Getting Http Host
 * @return string
 */
function http_host()
{
	return $_SERVER['HTTP_HOST'];
}

/**
 * Getting Request URI
 * @return string
 */
function request_uri()
{
	return $_SERVER['REQUEST_URI'];
}

/**
 * Getting Full Path of application
 * @return string
 */
function full_path()
{
	return request_scheme() . http_host() . request_uri();
}

/**
 * Getting base url of application
 * @return string
 */
function base_url()
{
	return request_scheme() . http_host() . BASE_DIR;
}

/**
 * Getting image file
 * @return string
 */
function get_image($filepath)
{
	return base_url() . '/public/images/' . $filepath;
}

/**
 * Getting CSS Style File
 * @return string
 */
function get_css($filepath)
{
	return base_url() . '/public/css/' . $filepath;
}

/**
 * Getting JS File
 * @return string
 */
function get_js($filepath)
{
	return base_url() . '/public/js/' . $filepath;
}

/**
 * Redirection
 * @return void
 */
function redirect($link)
{
	header('Location: ' . $link);
}

/**
 * Getting real ip address of visitor
 * @return string
 */
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

/**
 * Getting user agent
 * @return string
 */
function get_user_agent()
{
	return $_SERVER['HTTP_USER_AGENT'];
}

/**
 * Converting urls to clickable hyperlink in text
 * @param 	string $text
 * @return 	string
 */
function make_link($text)
{
	$pattern 	= '/(((http[s]?:\/\/(.+(:.+)?@)?)|(www\.))[a-z0-9](([-a-z0-9]+\.)*\.[a-z]{2,})?\/?[a-z0-9.,_\/~#&=:;%+!?-]+)/is';
	$text 		= preg_replace($pattern, ' <a href="$1">$1</a>', $text);
	$text 		= preg_replace('/href="www/', 'href="http://www', $text);
	return $text;
}

/**
 * Converting email adressess to clickable mailto in text
 * @param 	string $text
 * @return 	string
 */
function make_mailto($text)
{
    $regex 		= '/(\S+@\S+\.\S+)/';
    $replace 	= '<a href="mailto:$1">$1</a>';
    return preg_replace($regex, $replace, $text);
}

/**
 * Safe mail
 * @param 	string $email
 * @return 	string
 */
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