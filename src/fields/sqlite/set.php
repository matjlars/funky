<?php
namespace funky\fields\sqlite;

class set extends \funky\fields\field
{
	private $values = [];

	public function init($args=[])
	{
		if(empty($args['values']) || !is_array($args['values'])){
			throw new \Exception('field "set" requires an array of "values"');
		}

		$this->values = $args['values'];
	}

	// returns an array of all selected options
	public function get()
	{
		$result = [];
		$count = count($this->values);
		for($i = 0; $i < $count; $i++){
			$bit = 1 << $i;
			if(($this->val | $bit) == $this->val){
				$result[] = $this->values[$i];
			}
		}
		return $result;
	}

	// takes a comma separated string or an array of values
	public function set($val)
	{
		// an integer is perfect
		if(is_int($val)){
			$this->val = $val;
			return;
		}

		// now turn strings into an array:
		if(!is_array($val)) $val = explode(',', $val);

		// turn on each bit that was set
		$this->val = 0;
		foreach($val as $v){
			if($this->isVal($v)){
				$this->val |= (1<<$this->valToBit($v));
			}
		}
	}

	public function in($val)
	{
		$bit = $this->valToBit($val);
		if(($this->val | (1<<$bit)) == $this->val) return true;
		return false;
	}

	public function only($val)
	{
		$bit = $this->valToBit($val);
		if($this->val == $bit) return true;
		return false;
	}

	public function none()
	{
		if(empty($this->val)) return true;
		return false;
	}

	public function values()
	{
		return $this->values;
	}

	public function dbtype()
	{
		return 'integer';
	}

	// returns the bit for the given value
	// returns false if that value doesn't exist
	private function valToBit($val)
	{
		$count = count($this->values);
		for($i = 0; $i < $count; $i++){
			if($this->values[$i] == $val) return $i;
		}
		return false;
	}

	// returns true if the given val exists in the list of possible vals
	// otherwise, returns false.
	private function isVal($val)
	{
		if(in_array($val, $this->values)){
			return true;
		}else{
			return false;
		}
	}
}