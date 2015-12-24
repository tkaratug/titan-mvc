<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Template Plugin
 * 
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Template
{
	// Asset Selector
	public $asset;

	/**
	 * Setting CSS files.
	 * @param $css_file 	string	file path or url
	 * @param $source 	string	(local|remote)
	 * @return void
	 */
	public function set_css($css_file, $source = 'local')
	{
		if($source == 'remote') {
			$url = $css_file;
		} else {
			$url = 'public/css/' . $css_file;

			// Check is file exists
			if(!file_exists($url)) {
				$code	= 1005;
				$text	= "CSS Style dosyası bulunamadı: {$url}";
				require_once 'app/views/errors/error_system.php';
				die();
			}
		}

		$this->asset['header']['css'][]	= '<link rel="stylesheet" type="text/css" href="' . $url . '">';
	}

	/**
	 * Setting JS files.
	 * @param $js_file	string	file path or url
	 * @param $location 	string 	(header|footer)
	 * @param $source 	string	(local|remote)
	 * @return void
	 */
	public function set_js($js_file, $location = 'header', $source = 'local')
	{		
		if($source == 'remote') {
			$url = $js_file;
		} else {
			$url = 'public/js/' . $js_file;

			// Check is file exists
			if(!file_exists($url)) {
				$code	= 1006;
				$text	= "Javascript dosyası bulunamadı: {$url}";
				require_once 'app/views/errors/error_system.php';
				die();
			}
		}

		$this->asset[$location]['js'][]	= '<script type="text/javascript" src="' . $url . '"></script>';
	}

	/**
	 * Setting Meta Tags
	 * @param $meta_name 	string	meta tag name
	 * @param $meta_content string	meta tag content
	 * @return void
	 */
	public function set_meta($meta_name, $meta_content)
	{
		$this->asset['header']['meta'][] = '<meta name="' . $meta_name . '" content="' . $meta_content . '">';
	}

	/**
	 * Setting Page Title
	 * @param $title string
	 * @return void
	 */
	public function set_title($title)
	{
		$this->asset['header']['title'] = '<title>' . $title . '</title>';
	}

	/**
	 * Get CSS Files
	 * @return array
	 */
	public function get_css()
	{
		return $this->asset['header']['css'];
	}

	/**
	 * Get JS Files
	 * @param $location 	(header|footer)
	 * @return array
	 */
	public function get_js($location = 'header')
	{
		return $this->asset[$location]['js'];
	}

	/**
	 * Get Meta Tags
	 * @return array
	 */
	public function get_meta()
	{
		return $this->asset['header']['meta'];
	}
	
	/**
	 * Get Page Title
	 * @return string
	 */
	public function get_title()
	{
		return $this->asset['header']['title'];
	}

}
