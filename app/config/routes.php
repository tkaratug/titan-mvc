<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Routes
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

return [
	'anasayfa'				=> 'frontend/home/index',
	'username/([a-zA-Z]+)'	=> 'frontend/home/username/$1',
	'userid/(\d+)'			=> 'frontend/home/userid/$1',
	'backend'				=> 'backend/dashboard/index',
];