<?php
namespace funky\fields;

class flag extends \funky\fields\field{
	public function set($val){
		$this->val = boolval($val);
	}

	public function dbval(){
		if($this->val) return 1;
		return 0;
	}

	public function dbtype(){
		return 'bit(1)';
	}
}