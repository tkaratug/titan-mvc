<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Routes
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

return [
	'anasayfa'				=> 'Home/index',
	'username/([a-zA-Z]+)'	=> 'Home/username/$1',
	'userid/(\d+)'			=> 'Home/userid/$1'
];