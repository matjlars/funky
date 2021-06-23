<?php
namespace funky\fields;

class money extends \funky\fields\field{
	public function get(){
		return '$'.$this->val;
	}

	public function __toString(){
		return $this->get();
	}

	public function dbtype(){
		return 'decimal(10,2)';
	}

	public function dbval(){
		if(empty($this->val)) return '0.0';
		return $this->val;
	}
}
