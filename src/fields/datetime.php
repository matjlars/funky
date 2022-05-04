<?php
namespace funky\fields;

class datetime extends \funky\fields\field
{
	protected $nullable = false;

	public function init($args=[]){
		if(isset($args['default']) && $args['default'] == 'now') $this->val = time();

		// if there is no default, this field is nullable
		if(!isset($args['default'])) $this->nullable = true;

		// allow explicitly setting nullable
		if(isset($args['nullable'])) $this->nullable = boolval($args['nullable']);
	}

	public function set($val){
		if($this->nullable && empty($val)){
			parent::set(null);
		}else{
			parent::set(\strtotime($val));
		}
	}

	public function get(){
		if($this->nullable && empty($this->val)){
			return null;
		}else{
			return $this->format('m/d/Y g:ia');
		}
	}

	public function format($formatstring){
		return \date($formatstring, $this->val);
	}

	public function gettime(){
		return $this->val;
	}

	public function settime($time){
		$this->val = $time;
	}

	public function settonow(){
		$this->settime(\time());
	}

	public function dbval(){
		if($this->nullable && empty($this->val)) return null;
		return $this->format('Y-m-d H:i:s');
	}

	public function dbtype(){
		return 'datetime';
	}

	public function isnullable(){
		return $this->nullable;
	}

	public function is_null(){
		return $this->nullable && $this->val === null;
	}

	public function __toString(){
		if($this->nullable && empty($this->val)){
			return 'N/A';
		}else{
			return $this->format('m/d/Y g:ia');
		}
	}
}
