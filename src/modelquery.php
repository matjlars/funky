<?php
namespace funky;

// provides a really nice interface for getting 1 or many models objects with 1 db query
class modelquery implements \Iterator
{
	private $modelclass = '';
	private $where = array();
	private $order = NULL;
	private $limit = 0;
	private $offset = 0;
	private $models = null;
	private $it = 0;
	
	// $modelclass is the name of a class of a model.
	// for example, 'user'
	public function __construct($modelclass)
	{
		$this->modelclass = $modelclass;
	}
	
	public function islocked()
	{
		return !is_null($this->models);
	}
	
	// accepts either a string or an array.
	// if $cond is a string, it simply adds that as a WHERE condition that is ANDed with the others
	// if $cond is an array, each array element is added as a "key = value" with the value auto-escaped
	public function where($cond)
	{
		if($this->islocked()) throw new \exception('you cannot add any more where clauses to this modelquery because the query has already ran.');
		if(is_array($cond)){
			foreach($cond as $key=>$value){
				$this->where[] = '`'.$key.'`='.'"'.f()->db->escape($value).'"';
			}
		}else if(is_string($cond)){
			$this->where[] = $cond;
		}else{
			throw new \exception('modelquery->where() must be given an array or string. You gave it a "'.gettype($cond).'"');
		}
		return $this;
	}
	
	// DEPRECATED. Use order() instead.
	public function orderby($orderby)
	{
		if($this->islocked()) throw new \exception('you cannot order this modelquery anymore because the query has already ran.');
		$this->order = [$orderby];
		return $this;
	}

	// orders the results
	// pass the column name that you want to order by.
	// optionally pass "true" to $is_descending to order it descending.
	// otherwise, it defaults to ascending.
	// you can call this a second time to specify multiple columns to order by.
	// pass a NULL to the $col to clear this query's existing orderings.
	public function order($col, $is_descending=false){
		if($this->islocked()) throw new \exception('you cannot order this modelquery anymore because the query has already ran.');

		// clear if passed NULL
		if($col === NULL){
			$this->order = NULL;
			return;
		}

		// generate ORDER BY clause
		$clause = '`'.$col.'`';
		if($is_descending) $clause .= ' DESC';

		// add this clause to the array of ORDER BY clauses.
		if($this->order === NULL) $this->order = [];
		$this->order[] = $clause;

		return $this;
	}

	// accepts an int to limit the result set to a given size
	// use the number 0 to remove the limit
	public function limit($limit){
		if($this->islocked()) throw new \exception('you cannot limit this modelquery anymore because the query has already ran.');
		$this->limit = $limit;
		return $this;
	}

	// accepts an integer to page the result set ahead by the given number of results
	// for example, 0 means it doesn't skip any results (default)
	// another example: 1 means it will skip the first result.
	// for pagination, send your Results Per Page to limit() and your (RPP * page #) to this.
	public function offset($offset){
		if($this->islocked()) throw new \exception('you cannot offset this modelquery anymore because the query has already ran.');
		$this->offset = $offset;
		return $this;
	}
	
	// performs the query and returns an array of model objects
	public function toArray(){
		$this->run();
		return $this->models;
	}
	
	// returns the sql needed to run this query
	public function sql()
	{
		// SELECT
		$sql = 'SELECT ';
		if(!empty($this->limit)){
			$sql .= 'SQL_CALC_FOUND_ROWS ';
		}
		$sql .= '*';
		
		// FROM
		$modelclass = $this->modelclass;
		$sql .= ' FROM `'.$modelclass::table().'`';
		
		// WHERE
		if(!empty($this->where)){
			$sql .= ' WHERE '.implode(' AND ', $this->where);
		}
		
		// ORDER
		if($this->order !== NULL){
			$sql .= ' ORDER BY '.implode(', ', $this->order);
		}
		
		// LIMIT
		if(!empty($this->limit)){

			$sql .= ' LIMIT '.$this->limit;
		}

		// OFFSET
		if(!empty($this->offset)){
			$sql .= ' OFFSET '.$this->offset;
		}

		return $sql;
	}
	
	// returns the first model in the array of models.
	// returns false if there are none.
	public function first()
	{
		$this->run();
		if(empty($this->models)) return false;
		return array_shift($this->models);
	}
	public function count()
	{
		$this->run();
		return count($this->models);
	}
	
	// Iterator functions
	public function rewind()
	{
		$this->it = 0;
	}
	public function valid()
	{
		return $this->it < $this->count();
	}
	public function current()
	{
		return $this->models[$this->it];
	}
	public function key()
	{
		return $this->it;
	}
	public function next()
	{
		$this->it += 1;
	}
	private function run()
	{
		if(is_null($this->models)){
			$this->models = array();
			$modelclass = $this->modelclass;
			foreach(f()->db->query($this->sql()) as $row){
				$this->models[] = $modelclass::fromdata($row);
			}
		}
	}
}