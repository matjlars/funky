<?php
namespace funky;

class sqliteresult implements \Iterator
{
	private $pos;
	private $results;

	public function __construct($resource)
	{
		$this->pos = 0;
		$this->results = [];
		while($res = $resource->fetchArray(SQLITE3_ASSOC)){
			$this->results[] = $res;
		}
	}

	// Iterator functions:
	function rewind(){
		$this->pos = 0;
	}
	function current(){
		return $this->results[$this->pos];
	}
	function next(){
		$this->pos++;
	}
	function valid(){
		return $this->pos < count($this->results);
	}
	function key(){
		return $this->pos;
	}

	// returns the number of rows in the result set
	public function count()
	{
		return count($this->results);
	}

	// returns the first row or false if one doesn't exist
	public function row()
	{
		if(count($this->results) > 0) return $this->current();
		return false;
	}

	// returns all values for a given key in a string set format, like "(2,4,6,7)"
	public function set($key)
	{
		$set = '(';
		foreach($this as $row){
			$set .= $row[$key].',';
		}
		$set = rtrim($set, ',').')';
		return $set;
	}

	// returns an associative array mapping [$key1]=>$key2
	public function map($key1, $key2)
	{
		$map = [];
		foreach($this as $row){
			$map[$row[$key1]] = $row[$key2];
		}
		return $map;
	}

	// returns a single value from the first row with the given $key
	// returns false if there are no rows
	public function val($key)
	{
		$row = $this->row();
		if($row === false){
			return false;
		}else{
			return $row[$key];
		}
	}

	// returns an array of all the data for a given key
	public function arr($key)
	{
		$data = [];
		foreach($this as $row){
			$data[] = $row[$key];
		}
		return $data;
	}
}
