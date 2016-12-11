<?php
namespace funky\services;

using core\dbresult;

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
	
	// Runs the $sql query and returns a dbresult
	public function query($sql)
	{
		$resource = $this->mysqli->query($sql);
		if($resource === true) return true;
		if($resource == false){
			throw new \exception('error while running db query "'.$sql.'". error: "'.$this->mysqli->error.'"');
		}
		return new dbresult($resource);
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