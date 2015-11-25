<?php defined('DIRECT') OR exit('No direct script access allowed');

class Model {

    /*
    * PDO sınıf örneğinin barınacağı değişken
    */
    static $pdo = null;

    /*
	* Kullanacağımız veritabanı karakter seti
	*/
	static $charset = 'UTF8';

	/*
	* Son yapılan sorguyu saklar
	*/
	static $last_stmt = null;

	/*
	* PDO örneğini yoksa oluşturan, varsa
	* oluşturulmuş olanı döndüren metot
	*/
	public static function instance()
	{
		return self::$pdo == null ?	self::init() : self::$pdo;
	}

	/*
	* PDO'yu tanımlayan ve bağlantıyı
	* kuran metot
	*/
	public static function init()
	{
		global $config;

		self::$pdo = new PDO('mysql:host='.$config['db_host'].';dbname='.$config['db_name'].'',$config['db_username'],$config['db_password']);

		self::$pdo->exec('SET NAMES `' . self::$charset . '`');
		self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

		return self::$pdo;
	}

	/*
	* PDO'nun query metoduna bindings
	* ilave edilmiş metot
	*/
	public static function query($query, $bindings = null)
	{
		if(is_null($bindings))
		{
			if(!self::$last_stmt = self::instance()->query($query))
				return false;
		}
		else
		{
			self::$last_stmt = self::prepare($query);
			if(!self::$last_stmt->execute($bindings))
				return false;
		}

		return self::$last_stmt;
	}

	/*
	* Yapılan sorgunun ilk satırının
	* ilk değerini döndüren metod
	*/
	public static function getVar($query, $bindings = null)
	{
		if(!$stmt = self::query($query, $bindings))
			return false;

		return $stmt->fetchColumn();
	}

	/*
	* Yapılan sorgunun ilk satırını
	* döndüren metod
	*/
	public static function getRow($query, $bindings = null)
	{
		if(!$stmt = self::query($query, $bindings))
			return false;

		return $stmt->fetch();
	}

	/*
	* Yapılan sorgunun tüm satırlarını
	* döndüren metod
	*/
	public static function get($query, $bindings = null)
	{
		if(!$stmt = self::query($query, $bindings))
			return false;

		$result = array();

		foreach($stmt as $row)
			$result[] = $row;

		return $result;
	}

	/*
	 * Yapılan sorgunun sonucunda dönen satır sayısını veren metod
	 */
	public static function rowCount()
	{
		return self::$last_stmt->rowCount();
	}

	/*
	* Query metodu ile aynı işlemi yapar
	* fakat etkilenen satır sayısını
	* döndürür
	*/
	public static function exec($query, $bindings = null)
	{
		if(!$stmt = self::query($query, $bindings))
			return false;

		return $stmt->rowCount();
	}

	/*
	* Insert işlemi yapar
	* fakat son eklenen ID'yi döndürür
	*/
	public static function insert($table, $data)
	{
		$fieldKeys  = implode(",", array_keys($data));
		$fieldVals	= '';

		$bindings	= array();
		foreach($data as $key => $value) {
			$fieldVals	.= '?,';
			$bindings[] = $value;
		}
		$fieldVals 	= rtrim($fieldVals,',');

        $query 		= "INSERT INTO $table ($fieldKeys) VALUES ($fieldVals)";

		if(!$stmt = self::query($query, $bindings))
			return false;

		return self::$pdo->lastInsertId();
	}

	/*
	* Update işlemi yapar
	* fakat son eklenen ID'yi döndürür
	*/
	public static function update($table, $data, $cond)
	{
		$fields 	= null;
		$bindings	= array();

        foreach($data as $key => $value) {
            $fields 	.= "$key=?,";
            $bindings[] = $value;
        }
        $updatedFields = rtrim($fields,',');

        $query = "UPDATE $table SET $updatedFields WHERE $cond";

        if(!$stmt = self::query($query, $bindings))
			return false;

		return $stmt->rowCount();
	}

	/*
	* Delete işlemi yapar
	* fakat son eklenen ID'yi döndürür
	*/
	public static function delete($table, $cond)
	{
        $query = "DELETE FROM $table WHERE $cond";

        if(!$stmt = self::query($query))
			return false;

		return $stmt->rowCount();
	}

	/*
	* Son gerçekleşen sorgudaki (varsa)
	* hatayı döndüren metod
	*/
	public static function getLastError()
	{
		$error_info = self::$last_stmt->errorInfo();

		if($error_info[0] == 00000)
			return false;

		return $error_info;
	}

	/**
	 * Sorgu Debug
	 */
	public static function last_query()
	{
		return self::$last_stmt->queryString;
	}

	/*
	* Statik olarak çağırılan ve yukarıda olmayan 
	* tüm metodları PDO'da çağıran sihirli metot
	*/
	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array(
			array(self::instance(), $name),
			$arguments
		);
	}
    
}