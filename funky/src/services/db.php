<?php
namespace funky\services;

class db
{
	private $mysqli;
	
	public function __construct()
	{
		$this->mysqli = new mysqli(f()->config->db_server, f()->config->db_user, f()->config->db_password, f()->config->db_name);
		
		// make sure it connected:
		if($this->mysqli->connect_errno){
			throw new \exception('failed to connect to mysql: ('.$this->mysqli->connect_errno.') '.$this->mysqli->connect_error);
		}
	}
	
	// Runs the $sql query and returns a db_result
	public function query($sql)
	{
		$resource = $this->mysqli->query($sql);
		if($resource === true) return true;
		if($resource == false){
			throw new \exception('error while running db query "'.$sql.'". error: "'.$this->mysqli->error.'"');
		}
		return new db_result($resource);
	}
	
	// Inserts $data into $table, and returns the inserted PK
	public function insert($table,$data)
	{
		// validate inputs:
		if(empty($table)) throw new \exception('An error occured while inserting: no table specified in f()->db->insert()');
		if(empty($data)) throw new \exception('An error occured while inserting: no data given in f()->db->insert()');
		if(!is_array($data)) throw new \exception('An error occured while inserting: data is not an array in f()->db->insert()');
		
		// generate the sql
		$sql = 'INSERT INTO `'.$this->escape($table).'` SET ';
		foreach($data as $key=>$value)
		{
			if($value === null)
			{
				$sql .= '`'.$key.'`=NULL,';
			}
			else
			{
				$sql .= '`'.$key.'`'.'="'.$this->escape($value).'",';
			}
		}
		$sql = substr($sql,0,-1); // strip the last ','
		
		$result = $this->mysqli->query($sql);
		if(!$result) throw new \exception('An error occurred while running an SQL INSERT: "'.$sql.'" ERROR: '.$this->mysqli->error);
		return $this->mysqli->insert_id;
	}
	
	// Updates a $table with $data given $key = $value
	public function update($table,$data,$condkey,$condvalue)
	{
		// validate inputs:
		if(empty($table)) throw new \exception('no $table passed to f()->db->update()');
		if(empty($data)) throw new \exception('no $data passed to f()->db->update()');
		
		// Generate UPDATE SQL:
		$sql = 'UPDATE `'.$this->escape($table).'` SET ';
		foreach($data as $key=>$value)
		{
			if($value === null)
			{
				$sql .= '`'.$key.'`=NULL,';
			}
			else
			{
				$sql .= '`'.$key.'`="'.$this->escape($value).'",';
			}
		}
		$sql = substr($sql,0,-1); // strip the last ','
		$sql .= ' WHERE `'.$condkey.'` = "'.$this->escape($condvalue).'"';
		$result = $this->mysqli->query($sql);
		if(!$result) throw new \exception('An error occurred while running an SQL UPDATE: "'.$sql.'" ERROR: '.$this->mysqli->error);
	}
	
	// Determine if a table exists in the current database:
	public function table_exists($table)
	{
		if($this->query('SHOW TABLES LIKE "'.$table.'"')->count()) return true;
		return false;
	}
	
	// Safely escapes a value:
	public function escape($value)
	{
		return $this->mysqli->real_escape_string($value);
	}
	// returns an array of all options for the given SET or ENUM field in the given table.
	public function set_options($table, $field)
	{
		$sql = 'DESCRIBE '.$this->escape($table).' '.$this->escape($field);
		$row = $this->mysqli->query($sql)->fetch_assoc();
		return str_getcsv(trim(substr($row['Type'], 3), '()'), ',', "'");
	}
	// this function is because I won't remember which one to use later
	// also in case they ever differ
	public function enum_options($table, $field)
	{
		return $this->set_options($table, $field);
	}
	// this function returns all table names as an array
	public function tables()
	{
		$tables = array();
		$res = $this->mysqli->query('SHOW TABLES');
		while($row = $res->fetch_array(MYSQLI_NUM)){
			$tables[] = $row[0];
		}
		return $tables;
	}
}

class db_result implements Iterator
{
	private $resource;
	private $pos;
	
	public function __construct($resource)
	{
		// validate resource:
		if(empty($resource)){
			throw new \exception('resource empty in db_result::__construct(). it is type '.gettype($resource));
		}
		$this->resource = $resource;
	}
	public function __destruct()
	{
		$this->resource->free();
	}
	
	// Iterator Functions:
	function rewind()
	{
		$this->pos = 0;
	}
	function current()
	{
		if($this->pos >= $this->count()) return array();
		$this->resource->data_seek($this->pos);
		return $this->resource->fetch_assoc();
	}
	function next()
	{
		++$this->pos;
	}
	function valid()
	{
		return $this->pos < $this->count();
	}
	function key()
	{
		return $this->pos;
	}
	
	// this function allows direct access to a given row
	public function row($row=0)
	{
		$this->pos = $row;
		return $this->current();
	}
	public function count()
	{
		return $this->resource->num_rows;
	}
	
	// this gives you a SET (like "(0,1,2,3,4,5)") for a given key in the result
	public function set($key)
	{
		$set = '(';
		foreach($this as $row){
			$set .= $row[$key].',';
		}
		$set = rtrim($set,',').')';
		return $set;
	}
	
	// returns an associative array mapping [$key1]=>$key2 for each row
	public function map($key1,$key2)
	{
		$map = array();
		foreach($this as $row){
			$map[$row[$key1]] = $row[$key2];
		}
		return $map;
	}
	
	// returns a single value from the first row with the given $key
	// returns false if this key does not exist in the first row
	public function val($key)
	{
		$row = $this->current();
		if(!array_key_exists($key, $row)) return false;
		return $row[$key];
	}
	
	// returns an array of all of the data for a given key
	public function arr($key='')
	{
		$data = array();
		foreach($this as $row){
			$data[] = $row[$key];
		}
		return $data;
	}
}