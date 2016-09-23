<?php
class db extends j_service
{
	private $connection;
	
	public function __construct()
	{
		$this->connection = mysql_connect(j()->config->db_server,j()->config->db_user,j()->config->db_password);
		mysql_select_db(j()->config->db_name,$this->connection) or die('Error while trying to select database: "'.mysql_error().'"');
		if(!$this->connection) die('Failed to establish database connection. Please check the config file for correct credentials.');
	}
	
	// Runs the $sql query and returns a db_result
	public function query($sql)
	{
		$resource = mysql_query($sql) or die('An error occured while running an SQL query: SQL: "'.$sql.'" ERROR: '.mysql_error());
		if($resource===TRUE) return TRUE;
		return new db_result($resource);
	}
	
	// Inserts $data into $table, and returns the inserted PK
	public function insert($table,$data)
	{
		if(empty($table)) die('An error occured while inserting: no table specified in j()->db->insert()');
		if(empty($data)) die('An error occured while inserting: no data given in j()->db->insert()');
		if(!is_array($data)) die('An error occured while inserting: data is not an array in j()->db->insert()');
		$sql = 'INSERT INTO `'.mysql_real_escape_string($table).'` SET ';
		foreach($data as $key=>$value)
		{
			if($value === null)
			{
				$sql .= '`'.$key.'`=NULL,';
			}
			else
			{
				$sql .= '`'.$key.'`'.'="'.mysql_real_escape_string($value).'",';
			}
		}
		$sql = substr($sql,0,-1); // strip the last ','
		mysql_query($sql,$this->connection) or die('An error occurred while running an SQL INSERT: "'.$sql.'" ERROR: '.mysql_error());
		return mysql_insert_id();
	}
	
	// Updates a $table with $data given $key = $value
	public function update($table,$data,$condkey,$condvalue)
	{
		if(empty($data)) die('no $data passed to j()->db->update()');
		
		// Generate UPDATE SQL:
		$sql = 'UPDATE `'.mysql_real_escape_string($table).'` SET ';
		foreach($data as $key=>$value)
		{
			if($value === null)
			{
				$sql .= '`'.$key.'`=NULL,';
			}
			else
			{
				$sql .= '`'.$key.'`="'.mysql_real_escape_string($value).'",';
			}
		}
		$sql = substr($sql,0,-1); // strip the last ','
		$sql .= ' WHERE `'.$condkey.'` = "'.mysql_real_escape_string($condvalue).'"';
		mysql_query($sql,$this->connection) or die('An error occurred while running an SQL UPDATE: "'.$sql.'" ERROR: '.mysql_error());
	}
	
	// Determine if a table exists in the current database:
	public function table_exists($table)
	{
		if(mysql_num_rows(mysql_query('SHOW TABLES LIKE "'.$table.'"',$this->connection))) return true;
		return false;
	}
	
	// Safely escapes a value:
	public function escape($value)
	{
		return mysql_real_escape_string($value);
	}
}

class db_result implements Iterator
{
	private $resource;
	private $pos;
	private $count;
	
	public function __construct($resource)
	{
		$this->resource = $resource;
		$this->count = mysql_num_rows($resource);
	}
	
	// Iterator Functions:
	function rewind()
	{
		$this->pos = 0;
	}
	function current()
	{
		if($this->pos >= $this->count) return array();
		mysql_data_seek($this->resource,$this->pos);
		return mysql_fetch_assoc($this->resource);
	}
	function next()
	{
		++$this->pos;
	}
	function valid()
	{
		return $this->pos < $this->count;
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
		return $this->count;
	}
	
	// this gives you a SET (like "(0,1,2,3,4,5)") for a given key in the result
	public function set($key)
	{
		$set = '(';
		while($row = mysql_fetch_assoc($this->resource))
		{
			$set .= $row[$key].',';
		}
		$set = rtrim($set,',').')';
		return $set;
	}
	
	// returns an associative array mapping [$key1]=>$key2 for each row
	public function map($key1,$key2)
	{
		$map = array();
		while($row = mysql_fetch_assoc($this->resource))
		{
			$map[$row[$key1]] = $row[$key2];
		}
		return $map;
	}
	
	// returns a single value from the first row with the given $key
	public function val($key)
	{
		$row = mysql_fetch_assoc($this->resource);
		return $row[$key];
	}
	
	// returns an array of all of the data
	public function arr($key='')
	{
		$data = array();
		while($datum = mysql_fetch_assoc($this->resource))
		{
			if(empty($key))
			{
				$data[] = $datum;
			}
			else
			{
				$data[$datum[$key]] = $datum;
			}
		}
		return $data;
	}
}