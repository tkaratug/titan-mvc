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
	 * Set CSS files.
	 * @param 	$css_file 	string	(file path or url)
	 * @param 	$source 	string	(local|remote)
	 * @return 	void
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
	 * Set JS files.
	 * @param 	$js_file	string	(file path or url)
	 * @param 	$location 	string 	(header|footer)
	 * @param 	$source 	string	(local|remote)
	 * @return 	void
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
	 * Set Meta Tags
	 * @param 	$meta_name 		string	(meta tag name)
	 * @param 	$meta_content 	string	(meta tag content)
	 * @return 	void
	 */
	public function set_meta($meta_name, $meta_content)
	{
		$this->asset['header']['meta'][] = '<meta name="' . $meta_name . '" content="' . $meta_content . '">';
	}

	/**
	 * Set Page Title
	 * @param 	$title string
	 * @return 	void
	 */
	public function set_title($title)
	{
		$this->asset['header']['title'] = '<title>' . $title . '</title>';
	}

	/**
	 * Set favicon
	 * @param 	$image 	string
	 * @param 	$type 	string
	 * @return 	void
	 */ 	
	public function set_favicon($image)
	{
		$image_file = explode('.', $image);
		$extension 	= end($image_file);

		switch($extension) {
			case 'png' 	: $icon_type = 'image/png'; break;
			case 'ico' 	: $icon_type = 'image/x-icon'; break;
			default 	: $icon_type = 'image/x-icon'; break;
		}

		$this->asset['header']['favicon'] = '<link rel="shortcut icon" type="' . $icon_type . '" href="public/images/' . $image . '" />';
	}

	/**
	 * Get CSS Files
	 * @return array
	 */
	public function get_css()
	{
		if(array_key_exists('css', $this->asset['header']))
			return $this->asset['header']['css'];
		else
			return null;
	}

	/**
	 * Get JS Files
	 * @param 	$location 	(header|footer)
	 * @return 	array
	 */
	public function get_js($location = 'header')
	{
		if(array_key_exists($location, $this->asset) && array_key_exists('js', $this->asset[$location]))
			return $this->asset[$location]['js'];
		else
			return null;
	}

	/**
	 * Get Meta Tags
	 * @return array
	 */
	public function get_meta()
	{
		if(array_key_exists('meta', $this->asset['header']))
			return $this->asset['header']['meta'];
		else
			return null;
	}
	
	/**
	 * Get Page Title
	 * @return string
	 */
	public function get_title()
	{
		if(array_key_exists('title', $this->asset['header']))
			return $this->asset['header']['title'];
		else
			return null;
	}

	/**
	 * Get favicon
	 * @return string
	 */
	public function get_favicon()
	{
		if(array_key_exists('favicon', $this->asset['header']))
			return $this->asset['header']['favicon'];
		else
			return null;
	}

}