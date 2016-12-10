<?php
namespace funky\core;

// provides a really nice interface for getting 1 or many models objects with 1 db query
class modelquery
{
	private $modelclass = '';
	private $where = array();
	private $orderby = '';
	private $limit = 0;
	private $offset = 0;
	private $models = null;
	private $locked = false;
	
	// $modelclass is the name of a class of a model.
	// for example, 'user'
	public function __construct($modelclass)
	{
		$this->modelclass = $modelclass;
	}
	
	// accepts either a string or an array.
	// if $cond is a string, it simply adds that as a WHERE condition that is ANDed with the others
	// if $cond is an array, each array element is added as a "key = value" with the value auto-escaped
	public function where($cond)
	{
		if($this->locked) throw new \exception('you cannot add any more where clauses to this modelquery because the query has already ran.');
		if(is_array($cond)){
			foreach($cond as $key=>$value){
				$where[] = '`'.$key.'`='.'`'.f()->db->escape($value).'`';
			}
		}else if(is_string($cond)){
			$where[] = $cond;
		}else{
			throw new \exception('modelquery->where() must be given an array or string. You gave it a "'.gettype($cond).'"');
		}
	}

	// accepts a string to order the query, and therefore the eventual array of model objects
	// the format should be something like 'id ASC' or 'name DESC'
	public function orderby($orderby)
	{
		if($this->locked) throw new \exception('you cannot order this modelquery anymore because the query has already ran.');
		$this->orderby = $orderby;
	}

	// accepts an int to limit the result set to a given size
	// use the number 0 to remove the limit
	public function limit($limit)
	{
		if($this->locked) throw new \exception('you cannot limit this modelquery anymore because the query has already ran.');
		$this->limit = $limit;
	}
	// accepts an integer to page the result set ahead by the given number of results
	// for example, 0 means it doesn't skip any results (default)
	// another example: 1 means it will skip the first result.
	// for pagination, send your Results Per Page to limit() and your (RPP * page #) to this.
	public function offset($offset)
	{
		if($this->locked) throw new \exception('you cannot offset this modelquery anymore because the query has already ran.');
		$this->offset = $offset;
	}
	
	// performs the query and returns an array of model objects
	public function toArray()
	{
		// if we haven't loaded it yet, load it and lock it:
		if($this->models === null){
			$this->models = array();
			foreach(f()->db->query($this->sql()) as $row){
				$this->models[] = $modelclass::fromdata($row);
			}
			// Lock it to prevent further changes. This will surely help a dev not mess up
			$this->locked = true;
		}
		return $this->models;
	}
	
	// returns the sql needed to run this query
	public function sql()
	{
		// SELECT
		$sql = 'SELECT *';
		if(!empty($this->limit)){
			$sql .= ',CALC_FOUND_ROWS';
		}
		
		// FROM
		$sql .= ' FROM '.$this->modelclass::table();
		
		// WHERE
		if(!empty($this->where)){
			$sql .= ' WHERE '.implode(' AND ', $this->where);
		}
		
		// ORDER
		if(!empty($this->orderby)){
			$sql .= ' ORDER BY '.$this->orderby;
		}
		
		// LIMIT
		if(!empty($this->limit)){
			$sql .= ' LIMIT '.$this->limit;
			
			// OFFSET
			if(!empty($this->offset)){
				$sql .= ','.$this->offset;
			}
		}

		return $sql;
	}
}