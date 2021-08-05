<?php
namespace funky\fields;

class money extends \funky\fields\decimal{
	public function get(){
		return '$'.$this->val;
	}

	public function __toString(){
		return $this->get();
	}
}
