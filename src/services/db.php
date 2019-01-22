<?php
namespace funky\services;
use funky\dbresult;

class db
{
	private $service;
	private $type;
	
	public function __construct()
	{
		// use mysql if there's mysql config:
		if(isset(f()->config->mysql_user) && isset(f()->config->mysql_password) && isset(f()->config->mysql_name)){
			$server = 'localhost';
			if(isset(f()->config->mysql_server)) $server = f()->config->mysql_server;
			$this->type = 'mysql';
			$this->service = f()->mysql;

		// otherwise, use sqlite:
		}else{
			$this->type = 'sqlite';
			$this->service = f()->sqlite;
		}
	}

	// returns a string representing the db type ('mysql' or 'sqlite')
	public function type()
	{
		return $this->type;
	}
	
	// Runs the $sql query and returns a dbresult
	public function query($sql)
	{
		return $this->service->query($sql);
	}

	public function exec($sql)
	{
		return $this->service->exec($sql);
	}
	
	// Inserts $data into $table, and returns the inserted PK
	public function insert($table,$data)
	{
		return $this->service->insert($table, $data);
	}
	
	// Updates a $table with $data given $key = $value
	public function update($table, $data, $condkey, $condvalue)
	{
		return $this->service->update($table, $data, $condkey, $condvalue);
	}
	
	// Determine if a table exists in the current database:
	public function table_exists($table)
	{
		return $this->service->table_exists($table);
	}
	
	// Safely escapes a value:
	public function escape($value)
	{
		return $this->service->escape($value);
	}

	// returns an array of all options for the given SET or ENUM field in the given table.
	public function set_options($table, $field)
	{
		return $this->service->set_options($table, $field);
	}

	// this function is because I won't remember which one to use later
	// also in case they ever differ
	public function enum_options($table, $field)
	{
		return $this->service->enum_options($table, $field);
	}

	// this function returns all table names as an array
	public function tables()
	{
		return $this->service->tables();
	}
}