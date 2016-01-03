<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Debug Helper
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

/**
 * String Debug
 * @param 	string 	$data
 * @param 	bool 	$stop
 * @return 	string
 */
if( ! function_exists('debug')) {
	function debug($data, $stop = false)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';

		if($stop)
			die();
	}
}

/**
 * Query Debug
 * @param 	bool 	$stop
 * @return 	string
 */
if( ! function_exists('query_debug')) {
	function query_debug($stop = false)
	{
		debug(Model::last_query(), $stop);
	}
}

?>