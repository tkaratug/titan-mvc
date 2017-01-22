<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Template Plugin
 * 
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

use Windwalker\Edge\Cache\EdgeFileCache;
use Windwalker\Edge\Edge;
use Windwalker\Edge\Loader\EdgeFileLoader;

class Template
{
	public function render($file, $vars, $cache = false)
	{
		$paths = array(APP_DIR . '/views');

		if($cache === false)
			$this->edge = new Edge(new EdgeFileLoader($paths));
		else
			$this->edge = new Edge(new EdgeFileLoader($paths), null, new EdgeFileCache(APP_DIR . '/cache'));

		return $this->edge->render($file, $vars);
	}

}