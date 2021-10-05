<?php
namespace funky\fields;

class time extends \funky\fields\field
{
	protected $format;
	protected $allow_seconds = false;

	public function init($args){
		// set the default format:
		if(empty($args['format'])){
			$this->format = 'h:i A';
		}else{
			$this->format = $args['format'];
		}

		if(isset($args['default']) && $args['default'] == 'now') $this->val = time();
		if(!empty($args['allow_seconds'])) $this->allow_seconds = true;
	}

	public function set($val){
		parent::set(strtotime($val));
	}

	public function get(){
		return $this->format($this->format);
	}

	public function format($format){
		if(empty($this->val)) return '';
		return \date($format, $this->val);
	}

	// returns the format string for the form field view
	public function field_format(){
		if($this->allow_seconds){
			return 'H:i:s';
		}else{
			return 'H:i';
		}
	}

	public function dbval(){
		return $this->format('H:i:s');
	}

	public function dbtype(){
		return 'time';
	}
}