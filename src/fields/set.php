<?php
namespace funky\fields;

class set extends \funky\fields\field{
	// an array of strings that represents all possible values
	private $values;
	
	public function init($args){
		if(empty($args['values']) || !is_array($args['values'])) throw new \exception('field "set" requires an array of "values"');
		$this->values = $args['values'];
	}

	public function get(){
		if(empty($this->val)) return [];
		return explode(',', $this->val);
	}

	public function set($val){
		// convert an array to a comma separate string
		if(is_array($val)){
			$val = array_filter($val);
			$val = implode(',', $val);
		}
		parent::set($val);
	}

	public function in($val){
		return in_array($val, $this->get());
	}

	public function only($val){
		$arr = $this->get();
		if(count($arr) != 1) return false;
		if($arr[0] == $val) return true;
		return false;
	}

	public function none(){
		if(empty($this->val)) return true;
		return false;
	}

	public function values(){
		return $this->values;
	}

	public function dbtype(){
		// make a sql string of all the values with single quotes
		$escapedvals = array();
		foreach($this->values as $val){
			$escapedvals[] = "'$val'";
		}
		return 'set('.implode(',', $escapedvals).')';
	}
}