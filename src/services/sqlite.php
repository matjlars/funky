<?php
namespace funky\services;
use funky\sqliteresult;

class sqlite
{
	private $db;
	
	public function __construct()
	{
		$this->db = new \SQLite3(f()->path->php('db.sqlite'));
	}

	public function __destruct()
	{
		$this->db->close();
	}
	
	// Runs the $sql query and returns a dbresult
	public function query($sql)
	{
		$res = $this->db->query($sql);
		if($res === false){
			throw new \exception('error while running sqlite query "'.$sql.'". error: "'.$this->db->lastErrorMsg().'"');
		}
		return new sqliteresult($res);
	}

	public function exec($sql)
	{
		return $this->db->exec($sql);
	}
	
	// Inserts $data into $table, and returns the inserted PK
	public function insert($table, $data)
	{
		$count = count($data);

		$sql = 'INSERT INTO "'.$table.'" (';

		// list out all columns to insert into:
		$i = 0;
		foreach($data as $key=>$val){
			$sql .= $key;
			if($i < $count-1) $sql .= ',';
			$i++;
		}
		$sql .= ') VALUES (';
		
		// append prepared statement placeholders for all keys:
		$i = 0;
		foreach($data as $key=>$val){
			$sql .= ':'.$key;
			if($i < $count-1) $sql .= ',';
			$i++;
		}

		$sql .= ');';

		// prepare the statement:
		$statement = $this->db->prepare($sql);

		// bind all values:
		foreach($data as $key=>$val){
			$statement->bindValue(':'.$key, $val);
		}

		// execute it:
		$res = $statement->execute();

		if($res === false){
			throw new \Exception('error while inserting into "'.$table.'". error message: "'.$this->db->lastErrorMsg().'"');
		}

		return $this->db->lastInsertRowID();
	}
	
	// Updates a $table with $data given $key = $value
	public function update($table, $data, $condkey, $condvalue)
	{
		// Generate UPDATE SQL:
		$sql = 'UPDATE "'.$table.'" SET ';
		foreach($data as $key=>$value){
			if($value === null){
				$sql .= '`'.$key.'`=NULL,';
			}elseif(is_int($value)){
				$sql .= '`'.$key.'`='.$this->escape($value).',';
			}else{
				$sql .= '`'.$key.'`'.'="'.$this->escape($value).'",';
			}
		}
		$sql = substr($sql,0,-1); // strip the last ','
		$sql .= ' WHERE "'.$condkey.'" = "'.$this->escape($condvalue).'"';

		$result = $this->db->exec($sql);
		if($result === false) throw new \exception('error while updating: "'.$sql.'" error message: "'.$this->db->lastErrorMsg().'"');
	}
	
	// Determine if a table exists in the current database:
	public function table_exists($table)
	{
		$res = $this->db->querySingle('SELECT 1 FROM sqlite_master WHERE name = "'.$table.'"');

		// false means a failed query:
		if($res === false) throw new \Exception('error while checking if table '.$table.' exists. error message: "'.$this->db->lastErrorMsg().'"');

		// null means an empty result set, which means no table
		if(empty($res)) return false;

		// anything else (namely, "1") means the table exists
		return true;
	}
	
	// Safely escapes a value:
	public function escape($value)
	{
		return $this->db->escapeString($value);
	}

	// returns an array of all options for the given SET or ENUM field in the given table.
	public function set_options($table, $field)
	{
		throw new \Exception('sqlite does not support the set type');
	}

	// this function is because I won't remember which one to use later
	// also in case they ever differ
	public function enum_options($table, $field)
	{
		throw new \Exception('sqlite does not support the enum type');
	}

	// this function returns all table names as an array
	public function tables()
	{
		$res = $this->db->query('SELECT name FROM sqlite_master WHERE type="table"');

		$tables = [];
		foreach($res as $result){
			$row = $result->fetchArray(SQLITE3_NUM);
			$tables[] = $row[0];
		}
		return $tables;
	}
}