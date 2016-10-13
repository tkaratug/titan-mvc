<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Cache Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Cache
{
	private $config;
	private $filename;
	private $path;
	private $extension;
	private $default_expire_time;
	private $titan;

	function __construct()
	{
		$this->titan = Loader::getInstance();

		// Get config elements
		$this->config = $this->titan->config('cache');

		// Set configurations
		$this->path 				= APP_DIR . $this->config['path'];
		$this->extension 			= $this->config['extension'];
		$this->default_expire_time 	= $this->config['default_expire_time'];
	}

	/**
	 * Check cache directory exists
	 * @return 	string|bool
	 */
	private function check_cache_dir()
	{
		if(!is_dir($this->get_path()) && !mkdir($this->get_path(), 0775, true)) {
			echo 'Cache dizini oluşturulamadı ' . $this->get_path();
		} elseif(!is_readable($this->get_path()) || !is_writable($this->get_path())) {
			if(!chmod($this->get_path(), 0775)) {
				echo $this->get_path() . ' dizini okuma ve yazma izinlerine sahip olmalıdır';
			}
		} else {
			return true;
		}
	}

	/**
	 * Get cache directory
	 * @return 	string|bool
	 */
	private function get_cache_dir()
	{
		if($this->check_cache_dir() === true) {
			$filename = preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($this->get_cache()));
			return $this->get_path() . '/' . $this->hash_file($filename) . $this->get_extension();
		} else {
			return false;
		}
	}

	/**
	 * Load cached file content
	 * @return 	string|bool
	 */
	private function load_cache()
	{
		if($this->get_cache_dir() !== false) {
			if(file_exists($this->get_cache_dir())) {
				$file = file_get_contents($this->get_cache_dir());
				return json_decode($file, true);
			} else {
				return false;
			}			
		} else {
			return false;
		}
	}

	/**
	 * Write data into the cache file
	 * @param 	string 	$key
	 * @param 	string 	$data
	 * @param 	int 	$expiration
	 * @return 	void
	 */
	public function save($key, $data, $expiration = null)
	{
		if(is_null($expiration))
			$expiration = $this->default_expire_time;

		$stored_data = [
			'time'		=> time(),
			'expire'	=> $expiration,
			'data'		=> serialize($data)
		];

		$cache_content = $this->load_cache();

		if(is_array($cache_content) === true) {
			$cache_content[$key] = $stored_data;
		} else {
			$cache_content = [$key => $stored_data];
		}

		$cache_content = json_encode($cache_content);
		file_put_contents($this->get_cache_dir(), $cache_content);
	}

	/**
	 * Read data from the cache file
	 * @param 	string 	$key
	 * @return 	string
	 */
	public function read($key)
	{
		$cache_content = $this->load_cache();
		if(!isset($cache_content[$key]['data']))
			return null;
		else
			return unserialize($cache_content[$key]['data']);
	}

	/**
	 * Delete an item from cache file
	 * @param 	string 	$key
	 * @return 	string|bool
	 */
	public function delete($key)
	{
		$cache_content = $this->load_cache();

		if(is_array($cache_content)) {
			if(isset($cache_content[$key])) {
				unset($cache_content[$key]);
				$cache_content = json_encode($cache_content);
				file_put_contents($this->get_cache_dir(), $cache_content);
			} else {
				echo 'Hata: delete() - Key {'.$key.'} bulunamadı.';
			}
		}
	}

	/**
	 * Chech expired cached data
	 * @param 	int	 $time
	 * @param 	int	 $expiration
	 * @return 	bool
	 */
	private function check_expired($time, $expiration)
	{
		if($expiration !== 0) {
			$time_diff = time() - $time;
			if($time_diff > $expiration)
				return true;
			else
				return false;
		} else {
			return false;
		}
	}

	/**
	 * Delete expired cached data
	 * @return int
	 */
	public function delete_expired_cache()
	{
		$counter = 0;
		$cache_content = $this->load_cache();
		if(is_array($cache_content)) {
			foreach($cache_content as $key => $value) {
				if($this->check_expired($value['time'], $value['expire']) === true) {
					unset($cache_content[$key]);
					$counter++;
				}
			}

			if($counter > 0) {
				$cache_content = json_encode($cache_content);
				file_put_contents($this->get_cache_dir(), $cache_content);
			}
		}
		return $counter;
	}

	/**
	 * Delete all cached datas
	 * @return void
	 */
	public function clear()
	{
		if(file_exists($this->get_cache_dir())) {
			$file = fopen($this->get_cache_dir(), 'w');
			fclose($file);
		}
	}	

	/**
	 * Check cached datas with $key if exists
	 * @param 	string 	$key
	 * @return 	bool
	 */
	public function is_cached($key)
	{
		$this->delete_expired_cache();
		if($this->load_cache() != false) {
			$cache_content = $this->load_cache();
			return isset($cache_content[$key]['data']);
		}
	}

	/**
	 * Hash cache file name
	 * @param 	string 	$filename
	 * @return 	string
	 */
	private function hash_file($filename)
	{
		return md5($filename);
	}

	/**
	 * Set the name of cache file
	 * @param 	string 	$filename
	 * @return 	void
	 */
	public function set_cache($filename)
	{
		$this->filename = $filename;
	}

	/**
	 * Get the name of cache file
	 * @return 	string
	 */
	public function get_cache()
	{
		return $this->filename;
	}

	/**
	 * Set cache path
	 * @param 	string 	$path
	 * @return 	void
	 */
	public function set_path($path)
	{
		$this->path = APP_DIR . $path;
	}

	/**
	 * Get cache path
	 * @return 	string
	 */
	public function get_path()
	{
		return $this->path;
	}

	/**
	 * Set cache file extension
	 * @param 	string 	$ext
	 * @return 	void
	 */
	public function set_extension($ext)
	{
		$this->extension = $ext;
	}

	/**
	 * Get cache file extension
	 * @return 	string
	 */
	public function get_extension()
	{
		return $this->extension;
	}

}

?>
