<?php
namespace funky\fields;

class date extends \funky\fields\field{
	private $isnullable = true;

	public function init($args=[]){
		if(isset($args['isnullable'])) $this->isnullable = $args['isnullable'];
		if(isset($args['default']) && $args['default'] == 'now') $this->val = time();
	}

	// Accepts any date format that can be parsed by strtotime()
	public function set($val){
		if($this->isnullable && empty($val)){
			parent::set(null);
		}else{
			parent::set(strtotime($val));
		}
	}

	public function get(){
		return $this->format('m/d/Y');
	}

	// accepts a PHP date() format string and returns this date in that format
	// google "php date" if you want all the formatting options
	public function format($formatstring){
		// if we have no time, don't try to make a date
		if(empty($this->val)) return '';
		return \date($formatstring, $this->val);
	}

	public function time(){
		return $this->val;
	}

	public function dbval(){
		if(empty($this->val)) return null;
		return $this->format('Y-m-d');
	}

	public function isnullable(){
		return $this->isnullable;
	}

	public function dbtype(){
		return 'date';
	}
}