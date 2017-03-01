<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * File Upload Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Upload
{

	// File Field
	private $file 			= [];

	// Allowed Mime Types
	private $allowed_types 	= [];

	// Image Width
	private $max_width 		= 0;

	// Image Height
	private $max_height		= 0;

	// Max. File Size (KB)
	private $max_size 		= 0;

	// Upload Path
	private $upload_path 	= false;

	// Error
	private $error 			= false;

	/**
	 * Set configurations
	 *
	 * @param $config array
	 * @return void
	 */
	public function config($config = [])
	{
		if (array_key_exists('allowed_types', $config))
			$this->allowed_types = $config['allowed_types'];

		if (array_key_exists('max_width', $config))
			$this->max_width = $config['max_width'];

		if (array_key_exists('max_height', $config))
			$this->max_height = $config['max_height'];

		if (array_key_exists('max_size', $config))
			$this->max_size = $config['max_size'];

		if (array_key_exists('upload_path', $config))
			$this->upload_path = $config['upload_path'];
	}

	/**
	 * Start Upload Process
	 * 
	 * @return boolean
	 */
	public function handle($field)
	{
		$this->file = $field;

		if ($this->check_allowed_types() && $this->check_max_size() && $this->check_upload_path()) {
			
			if (is_uploaded_file($this->file['tmp_name'])) {
				if (move_uploaded_file($this->file['tmp_name'], $this->upload_path . '/' . $this->file['name']))
					$sonuc = true;
				else {
					$this->error = lang('upload', 'upload_error');
					$sonuc = false;
				}
			} else {
				$this->error = lang('upload', 'upload_error');
				$sonuc = false;
			}

			return $sonuc;

		} else {
			return false;
		}
	}

	/**
	 * Check Allowed File Extensions
	 *
	 * @return boolean
	 */
	private function check_allowed_types()
	{
		if (count($this->allowed_types) > 0) {
			
			$filepath	= pathinfo($this->file['name']);
			$extension 	= $filepath['extension'];

			if (!in_array($extension, $this->allowed_types)) {
				$this->error = lang('upload', 'file_type_error');
				return false;
			} else {
				if (in_array($extension, ['jpg','png','gif'])) {
					if ($this->max_width > 0 || $this->max_height > 0) {
						list($width, $height) = getimagesize($this->file['tmp_name']);

						if($width > $this->max_width || $height > $this->max_height) {
							$this->error = lang('upload', 'max_dimension_error', ['%s' => $this->max_width, '%t' => $this->max_height]);
							return false;
						}
					}
				}
			}

		} 

		return true;
	}

	/**
	 * Check Max File Size
	 *
	 * @return boolean
	 */
	private function check_max_size()
	{
		if ($this->max_size > 0) {

			if ($this->file['size'] > ($this->max_size * 1024)) {
				echo $this->file['size'];
				$this->error = lang('upload', 'max_size_error', $this->max_size);
				return false;
			}

		}

		return true;
	}

	/**
	 * Check Upload Path
	 * 
	 * @return boolean
	 */
	private function check_upload_path()
	{
		if ($this->upload_path === false) {
			$this->error = lang('upload', 'upload_path_needed_error');
			return false;
		} else {
			if (!file_exists($this->upload_path)) {
				$this->error = lang('upload', 'wrong_upload_path_error', $this->upload_path);
				return false;
			} else {
				if(!is_writable($this->upload_path)) {
					$this->error = lang('upload', 'permission_error');
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get Error Messages
	 *
	 * @return string
	 */
	public function getErrors()
	{
		return $this->error;
	}
	
}