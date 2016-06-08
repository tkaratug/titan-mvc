<?php
/**
 * Titan SQL query builder library for PHP
 *
 * @author 		Turan Karatuğ - <tkaratug@hotmail.com.tr> - <http://turankaratug.com>
 * @version 	1.0.0
 * @copyright 	2016
 * @license 	The MIT License (MIT) - Copyright (c) - http://opensource.org/licenses/MIT
 */

class Database
{

	// PDO instance
	private $pdo 			= null;

	// SQL query
	private $sql 			= null;

	// Last statement
	private $statement 		= null;

	// Selected columns
	private $select 		= '*';

	// From table
	private $from 			= null;

	// Where string
	private $where 			= [];

	// Join string
	private $join 			= [];

	// OrderBy string
	private $order_by 		= [];

	// Having string
	private $having 		= [];

	// GroupBy string
	private $group_by 		= null;

	// Limit string
	private $limit 			= null;

	// Total row count
	private $num_rows 		= 0;

	// Last insert id
	private $insert_id		= null;

	// Table prefix
	private $prefix 		= null;

	// Error
	private $error 			= null;

	// Instance
	private static $instance;

	// Getting instance
	public static function init($config = [])
	{
		if (null === static::$instance) {
			static::$instance = new static($config);
		}

		return self::$instance;
	}

	function __construct($config = [])
	{
		$config['db_driver']	= (@$config['db_driver']) ? $config['db_driver'] : 'mysql';
		$config['db_host']		= (@$config['db_host']) ? $config['db_host'] : 'localhost';
		$config['db_charset']	= (@$config['db_charset']) ? $config['db_charset'] : 'utf8';
		$config['db_collation']	= (@$config['db_collation']) ? $config['db_collation'] : 'utf8_general_ci';
		$config['db_prefix']	= (@$config['db_prefix']) ? $config['db_prefix'] : '';

		// Setting prefix
		$this->prefix = $config['db_prefix'];

		$dsn = '';

		// Setting connection string
		if($config['db_driver'] == 'mysql' || $config['db_driver'] == 'pgsql' || $config['db_driver'] == '') {
			$dsn = $config['db_driver'] . ':host=' . $config['db_host'] . ';dbname=' . $config['db_name'];
		} elseif($config['db_driver'] == 'sqlite') {
			$dsn = 'sqlite:' . $config['db_name'];
		} elseif($config['db_driver'] == 'oracle') {
			$dsn = 'oci:dbname=' . $config['db_host'] . '/' . $config['db_name'];
		}

		// Connecting to server
		try
		{
			$this->pdo = new \PDO($dsn, $config['db_user'], $config['db_pass']);
			$this->pdo->exec("SET NAMES '" . $config['db_charset'] . "' COLLATE '" . $config['db_collation'] . "'");
			$this->pdo->exec("SET CHARACTER SET '" . $config['db_charset'] . "'");
			$this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
		}
		catch(\PDOException $e)
		{
			die('Cannot connect to Database with PDO.<br /><br />'.$e->getMessage());
		}

		return $this->pdo;
	}

	/**
	 * Defines columns to select
	 * @param 	$select
	 * @return 	$this
	 */
	public function select($select = null)
	{
		if(!is_null($select))
			$this->select = $select;

		return $this;
	}

	/**
	 * Defines table
	 * @param 	$from
	 * @return 	$this
	 */
	public function from($from)
	{
		if(is_array($from)) {
			$table = '';
			foreach($from as $key) {
				$table .= $this->prefix . $key . ', ';
			}

			$this->from = rtrim($table, ', ');
		} else {
			$this->from = $this->prefix . $from;
		}

		return $this;
	}

	/**
	 * Defines 'join' operation
	 * @param 	$table
	 * @param 	$op
	 * @param 	$join
	 * @return 	$this
	 */
	public function join($table, $op, $join = 'INNER')
	{
		$this->join[] = ' ' . strtoupper($join) . ' JOIN ' . $this->prefix . $table . ' ON ' . $op;

		return $this;
	}

	/**
	 * Defines 'where' operation
	 * @param 	$column
	 * @param 	$value
	 * @param 	$mark
	 * @param 	$logic
	 * @return 	$this
	 */
	public function where($column, $value = '', $mark = '=', $logic = 'AND')
	{
		$this->where[] = [
			'column'	=> $column,
			'value'		=> $value,
			'mark'		=> $mark,
			'logic'		=> $logic
		];

		return $this;
	}

	/**
	 * Defines 'or where' operation
	 * @param 	$column
	 * @param 	$value
	 * @param 	$mark
	 * @return 	$this
	 */
	public function or_where($column, $value = '', $mark = '=')
	{
		$this->where($column, $value, $mark, 'OR');

		return $this;
	}

	/**
	 * Defines 'order by' operation
	 * @param 	$column
	 * @param 	$sort
	 * @return 	$this
	 */
	public function order_by($column, $sort = 'asc')
	{
		$this->order_by[] = [
			'column'	=> $column,
			'sort'		=> $sort
		];

		return $this;
	}

	/**
	 * Defines 'limit' operation
	 * @param 	$limit
	 * @param 	$start
	 * @return 	$this
	 */
	public function limit($limit, $start = 0)
	{
		$this->limit = ' LIMIT ' . $start . ', ' . $limit;

		return $this;
	}

	/**
	 * Defines 'group by' operation
	 * @param 	$column
	 * @return 	$this
	 */
	public function group_by($column)
	{
		$this->group_by = ' GROUP BY ' . $column;

		return $this;
	}

	/**
	 * Defines 'having' operation
	 * @param 	$column
	 * @param 	$value
	 * @param 	$mark
	 * @param 	$logic
	 * @return 	$this
	 */
	public function having($column, $value = '', $mark = '=', $logic = 'AND')
	{
		$this->having[] = [
			'column'	=> $column,
			'value'		=> $value,
			'mark'		=> $mark,
			'logic'		=> $logic
		];

		return $this;
	}

	/**
	 * Defines 'or having' operation
	 * @param 	$column
	 * @param 	$value
	 * @param 	$mark
	 * @return 	$this
	 */
	public function or_having($column, $value = '', $mark = '=')
	{
		$this->having($column, $value, $mark, 'OR');

		return $this;
	}

	/**
	 * Defines 'like' opertaion
	 * @param 	$column
	 * @param 	$value
	 * @param 	$logic
	 * @return 	$this
	 */
	public function like($column, $value = '', $logic = 'AND')
	{
		$this->where($column, $value, ' LIKE ', $logic);

		return $this;
	}

	/**
	 * Defines 'or like' operation
	 * @param 	$column
	 * @param 	$value
	 * @return 	$this
	 */
	public function or_like($column, $value = '')
	{
		$this->like($column, $value, 'OR');

		return $this;
	}

	/**
	 * Defines 'not like' operation
	 * @param 	$column
	 * @param 	$value
	 * @param 	$logic
	 * @return 	$this
	 */
	public function not_like($column, $value = '', $logic = 'AND')
	{
		$this->where($column, $value, ' NOT LIKE ', $logic);

		return $this;
	}

	/**
	 * Defines 'or not like' operation
	 * @param 	$column
	 * @param 	$value
	 * @return 	$this
	 */
	public function or_not_like($column, $value = '')
	{
		$this->not_like($column, $value, 'OR');

		return $this;
	}

	/**
	 * Defines 'in' operation
	 * @param 	$column
	 * @param 	$list
	 * @param 	$logic
	 * @return 	$this
	 */
	public function in($column, $list = [], $logic = 'AND')
	{
		$in_list = '';

		foreach($list as $element) {
			$in_list .= $this->escape($element) . ',';
		}

		$in_list = '(' . rtrim($in_list, ',') . ')';

		$this->where($column, $in_list, ' IN ', $logic);

		return $this;
	}

	/**
	 * Defines 'or in' operation
	 * @param 	$column
	 * @param 	$list
	 * @return 	$this
	 */
	public function or_in($column, $list = [])
	{
		$this->in($column, $list, 'OR');

		return $this;
	}

	/**
	 * Defines 'not in' operation
	 * @param 	$column
	 * @param 	$list
	 * @param 	$logic
	 * @return 	$this
	 */
	public function not_in($column, $list = [], $logic = 'AND')
	{
		$in_list = '';

		foreach($list as $element) {
			$in_list .= $this->escape($element) . ',';
		}

		$in_list = '(' . rtrim($in_list, ',') . ')';

		$this->where($column, $in_list, ' NOT IN ', $logic);

		return $this;
	}

	/**
	 * Defines 'or not in' operation
	 * @param 	$column
	 * @param 	$list
	 * @return 	$this
	 */
	public function or_not_in($column, $list = [])
	{
		$this->not_in($column, $list, 'OR');

		return $this;
	}

	/**
	 * Execute Select statements
	 * @return 	void
	 */
	private function run()
	{
		// JOIN
		if(count($this->join) > 0) {
			foreach($this->join as $join) {
				$this->sql .= ' ' . $join;
			}
		}

		// WHERE
		if(count($this->where) > 0) {
			$this->sql .= ' WHERE ';
			foreach($this->where as $key => $val) {
				if($key == 0) {
					if($val['mark'] == ' IN ' || $val['mark'] == ' NOT IN ') 
						$this->sql .= $val['column'] . $val['mark'] . $val['value'] . ' ';
					else
						$this->sql .= $val['column'] . $val['mark'] . $this->escape($val['value']) . ' ';
				} else {
					if($val['mark'] == ' IN ' || $val['mark'] == ' NOT IN ') 
						$this->sql .= $val['logic'] . ' ' . $val['column'] . $val['mark'] . $val['value'] . ' ';
					else
						$this->sql .= $val['logic'] . ' ' . $val['column'] . $val['mark'] . $this->escape($val['value']) . ' ';
				}
			}
			$this->sql = rtrim($this->sql);
		}

		// GROUP BY
		if(!is_null($this->group_by)) {
			$this->sql .= $this->group_by;
		}

		// HAVING
		if(count($this->having) > 0) {
			$this->sql .= ' HAVING ';
			foreach($this->having as $key => $val) {
				if($key == 0) {
					$this->sql .= $val['column'] . $val['mark'] . $this->escape($val['value']) . ' ';
				} else {
					$this->sql .= $val['logic'] . ' ' . $val['column'] . $val['mark'] . $this->escape($val['value']) . ' ';
				}
			}
			$this->sql = rtrim($this->sql);
		}

		// ORDER BY
		if(count($this->order_by) > 0) {
			$this->sql .= ' ORDER BY ';
			foreach($this->order_by as $key => $val) {
				$this->sql .= $val['column'] . ' ' . $val['sort'] . ', ';
			}
			$this->sql = rtrim($this->sql, ', ');
		}

		// LIMIT
		if(!is_null($this->limit)) {
			$this->sql .= $this->limit;
		}
	}

	/**
	 * Fetch one row
	 * @param 	$fetch
	 * @return 	array|object
	 */
	public function row($fetch = 'object')
	{
		// Run Query
		$query = $this->pdo->query($this->sql);

		try
		{
			if($fetch == 'array')
				$row = $query->fetch(\PDO::FETCH_ASSOC);
			else
				$row = $query->fetch(\PDO::FETCH_OBJ);

			// Reset
			$this->reset();

			return $row;
		}
		catch(\PDOException $e)
		{
			$this->error($e->getMessage());
		}
	}

	/**
	 * Fetch multi-rows
	 * @param 	$fetch
	 * @return 	array|object
	 */
	public function results($fetch = 'object')
	{
		// Run Query
		$query = $this->pdo->query($this->sql);

		try
		{
			// Fetch
			if($fetch == 'array')
				$result = $query->fetchAll(\PDO::FETCH_ASSOC);
			else
				$result = $query->fetchAll(\PDO::FETCH_OBJ);

			// Row Count
			$this->num_rows = $query->rowCount();

			// Reset
			$this->reset();

			return $result;
		}
		catch(\PDOException $e)
		{
			$this->error($e->getMessage());
		}
	}

	/**
	 * Execute custom query
	 * @param 	$query
	 * @param 	$fetch
	 * @return 	mixed
	 */
	public function query($query, $fetch = 'object')
	{
		$str = stristr($query, 'SELECT');
		if($str) {
			$this->sql = $query;
			return $this;
		} else {
			$this->statement = $query;

			$run = $this->pdo->query($query);
			if(!$run) {
				$this->error($this->pdo->errorInfo()[2]);
			} else {
				return $run;
			}
		}
	}

	/**
	 * Prepare and run 'SELECT' query
	 * @param 	$table
	 * @return 	$this
	 */
	public function get($table = null)
	{
		if(is_null($table))
			$this->sql = 'SELECT ' . $this->select . ' FROM ' . $this->from;
		else
			$this->sql = 'SELECT ' . $this->select . ' FROM ' . $this->prefix . $table;

		$this->run();

		return $this;
	}

	/**
	 * Insert operation
	 * @param 	$table
	 * @param 	$data
	 * @return 	mixed
	 */
	public function insert($table, $data = [])
	{
		$this->sql = 'INSERT INTO ' . $this->prefix . $table . ' SET ';

		$col 	= [];
		$val 	= [];
		$stmt 	= [];
		foreach($data as $column => $value) {
			$val[] 	= $value;
			$col[] 	= $column . ' = ? ';
			$stmt[] = $column . '=' . $this->escape($value);
		}

		$this->statement = $this->sql . implode(', ', $stmt);
		$this->sql .= implode(',', $col);

		try
		{
			$query 				= $this->pdo->prepare($this->sql);
			$insert 			= $query->execute($val);
			$this->insert_id 	= $this->pdo->lastInsertId();
			return $insert;
		}
		catch(\PDOException $e)
		{
			$this->error($e->getMessage());
		}
	}

	/**
	 * Update operation
	 * @param 	$table
	 * @param 	$data
	 * @return 	mixed
	 */
	public function update($table, $data = [])
	{
		$this->sql = 'UPDATE ' . $this->prefix . $table . ' SET ';

		$col 	= [];
		$val 	= [];
		$stmt 	= [];
		foreach($data as $column => $value) {
			$val[] 	= $value;
			$col[] 	= $column . ' = ? ';
			$stmt[]	= $column . '=' . $this->escape($value);
		}

		$this->statement = $this->sql . implode(', ', $stmt);
		$this->sql .= implode(',', $col);

		// WHERE
		if(count($this->where) > 0) {
			$this->sql 			.= ' WHERE ';
			$this->statement 	.= ' WHERE ';
			foreach($this->where as $key => $value) {
				if($key == 0) {
					$this->statement 	.= $value['column'] . $value['mark'] . $this->escape($value['value']) . ' ';
					$this->sql 			.= $value['column'] . $value['mark'] . '? ';
				} else {
					$this->statement 	.= $value['logic'] . ' ' .$value['column'] . $value['mark'] . $this->escape($value['value']) . ' ';
					$this->sql 			.= $value['logic'] . ' ' . $value['column'] . $value['mark'] . '? ';
				}
				$val[] = $value['value'];
			}
			$this->sql 			= rtrim($this->sql);
			$this->statement 	= rtrim($this->statement);
		}

		try
		{
			$query 		= $this->pdo->prepare($this->sql);
			$update 	= $query->execute($val);
			return $update;
		}
		catch(\PDOException $e)
		{
			$this->error($e->getMessage());
		}
	}

	/**
	 * Delete operation
	 * @param 	$table
	 * @return 	mixed
	 */
	public function delete($table)
	{
		$this->sql = 'DELETE FROM ' . $this->prefix . $table;

		// WHERE
		if(count($this->where) > 0) {
			$this->sql 			.= ' WHERE ';
			$this->statement 	= $this->sql;
			foreach($this->where as $key => $value) {
				if($key == 0) {
					$this->statement 	.= $value['column'] . $value['mark'] . $this->escape($value['value']) . ' ';
					$this->sql 			.= $value['column'] . $value['mark'] . '? ';
				} else {
					$this->statement 	.= $value['logic'] . ' ' .$value['column'] . $value['mark'] . $this->escape($value['value']) . ' ';
					$this->sql 			.= $value['logic'] . ' ' . $value['column'] . $value['mark'] . '? ';
				}
				$val[] = $value['value'];
			}
			$this->sql 			= rtrim($this->sql);
			$this->statement 	= rtrim($this->statement);
		}

		try
		{
			$query 	= $this->pdo->prepare($this->sql);
			$delete = $query->execute($val);
			return $delete;
		}
		catch(\PDOException $e)
		{
			$this->error($e->getMessage());
		}
	}

	/**
	 * Returns id of last inserted row
	 * @return 	int
	 */
	public function insert_id()
	{
		return $this->insert_id;
	}

	/**
	 * Returns number of rows
	 * @return 	int
	 */
	public function num_rows()
	{
		return $this->num_rows;
	}

	/**
	 * Returns last executed sql statement
	 * @return 	string
	 */
	public function last_query()
	{
		if(is_null($this->statement))
			return $this->sql;
		else
			return $this->statement;
	}

	/**
	 * Escape data for where operation
	 * @param 	$data
	 * @return 	string
	 */
	private function escape($data)
	{
		return $this->pdo->quote(trim($data));
	}

	/**
	 * Reset variables
	 * @return 	void
	 */
	private function reset()
	{
		$this->where 		= [];
		$this->join 		= [];
		$this->order_by 	= [];
		$this->having 		= [];
		$this->insert_id 	= null;
		$this->error 		= null;
		return;
	}

	/**
	 * Return Errors
	 * @param 	$message
	 * @return 	string
	 */
	public function error($message = null)
	{
		$err 	= '<div style="border: 1px solid #ccc; padding: 10px;">'
				. '<p>DB HATASI: </p>'
				. '<p><b>SORGU:</b> "' . $this->last_query() . '"</p>'
				. '<p><b>HATA:</b> ' . $message . '</p>'
				. '</div>';

		die($err);
	}

	private function __clone()
    {
    }

	function __destruct()
	{
		$this->pdo = null;
	}

}

?>
