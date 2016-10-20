<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Hooks
 * 
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

return [
	// Sample Hook
	'hook_name'	=> [
		'filename'	=> 'FileName', // filename that contains classes and functions
		'class'		=> 'ClassName', // class name in the hook file
		'method'	=> 'MethodName', // function name in the hook file
		'params'	=> ['key' => 'value'] // parameters to pass to function
	],
];