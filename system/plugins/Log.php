<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Logging Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Log
{

	protected static $log_levels = [
		0 => 'EMERGENCY',
		1 => 'ALERT',
		2 => 'CRITICAL',
		3 => 'ERROR',
		4 => 'WARNING',
		5 => 'NOTICE',
		6 => 'INFO',
		7 => 'DEBUG'
	];

	/**
	 * Write log
	 * @param 	int		$level
	 * @param 	string 	$message
	 * @return 	void 	
	 */
	public static function write($level, $message)
	{
		$log_text = date('Y-m-d H:i:s') . ' ---> ' . self::$log_levels[$level] . ': ' . $message;
		self::save_log($log_text);
	}

	/**
	 * Saving Logs
	 * @param 	string $log_text
	 * @return 	void
	 */
	private static function save_log($log_text)
	{
		$file_name 	= 'log-' . date('Y-m-d') . '.txt';
		$file 		= fopen('app/logs/' . $file_name, "a");
		
		if(fwrite($file, $log_text . "\n") === false)
			echo '<strong>Log Error:</strong> Log dosyası oluşturulamadı. Yazma izinlerini kontrol ediniz.';

		fclose($file);
	}

}