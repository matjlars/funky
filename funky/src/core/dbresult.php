<?php
namespace funky\core;

class dbresult implements \Iterator
{
	private $resource;
	private $pos;
	
	public function __construct($resource)
	{
		// validate resource:
		if(empty($resource)){
			throw new \exception('resource empty in dbresult::__construct(). it is type '.gettype($resource));
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
