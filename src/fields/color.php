<?php
namespace funky\fields;

class color extends field{
	// $this->val is a 6 character string containing the hex representation
	//   it does not include the "#" at the beginning because that is on 100% of color values.

	protected $isnullable = false;

	public function init($args){
		if(isset($args['isnullable'])) $this->isnullable = $args['isnullable'];
		if(isset($args['default'])){
			$this->set($args['default']);
		}else{
			if($this->isnullable){
				$this->val = null;
			}else{
				$this->val = '000000';
			}
		}
	}

	public function get(){
		return '#'.$this->val;
	}

	public function __toString(){
		return $this->get();
	}

	public function set($val){
		$val = trim($val, '#');

		// convert 3 digit to 6 digit
		if(strlen($val) == 3){
			// lol look at this weird line of code
			$val = $val[0].$val[0].$val[1].$val[1].$val[2].$val[2];
		}

		$this->val = $val;
	}

	public function dbtype(){
		return 'char(6)';
	}
	public function isnullable(){
		return $this->isnullable;
	}
	public function dbval(){
		if(empty($this->val)) return null;
		return $this->val;
	}
}