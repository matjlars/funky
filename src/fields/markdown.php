<?php
namespace funky\fields;

class markdown extends \funky\fields\field{
	public function set($val){
		$val = trim($val);
		parent::set($val);
	}

	public function render(){
		return f()->markdown->render($this->val);
	}

	public function __toString(){
		return $this->render();
	}

	public function dbtype(){
		return 'text';
	}
}