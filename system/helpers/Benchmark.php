<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Benchmark Helper
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

/**
 * Getting memory usage
 * @return string
 */
if ( ! function_exists('memory_usage')) {
	function memory_usage()
	{
		return round(memory_get_usage()/1024,2) . ' Kb';
	}
}

/**
 * Getting max memory usage
 * @return string
 */
if ( ! function_exists('memory_max_usage')) {
	function memory_max_usage()
	{
		return round(memory_get_peak_usage()/1024,2) . ' Kb';
	}
}

/**
 * Getting server load time
 * @return string
 */
if ( ! function_exists('server_load')) {
	function server_load()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		    return '<span style="color: #bc5858;"><strong>UYARI:</strong> Windows sunucuda server load bilgisi alınamamaktadır</span>';
		} else {
		    return sys_getloadavg()[0];
		}
	}
}


?>