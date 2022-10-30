<?php
namespace funky\fields;

class enum extends \funky\fields\field{
	private $values = [];
	private $option_labels = [];
	private $isnullable = true;
	private $null_label;
	
	public function init($args){
		if(empty($args['values'])) throw new \exception('enum field '.$this->name.' requires a "values" arg. this should contain strings that are the enum keys in the database');
		if(!is_array($args['values'])) throw new \exception('enum field '.$this->name.' has a "values" arg, but it is not an array. It should be an array of strings for the enum keys');
		$this->values = $args['values'];

		if(!empty($args['option_labels'])) $this->option_labels = $args['option_labels'];

		// if there is a default, set that and we will never need a null value
		if(isset($args['default'])){
			$this->val = $args['default'];
			$this->isnullable = false;
		}else{
			$this->val = null;
			$this->isnullable = true;
		}
		
		// validate the value
		$this->validators[] = function($val){
			if(empty($val)) return;
			if(!in_array($val, $this->values)) return 'contains an invalid value ('.$val.'). It should be one of ['.implode(',',$this->values).']';
		};
	}

	public function set($val){
		if(empty($val) && $this->isnullable){
			$val = null;
		}
		parent::set($val);
	}

	public function values(){
		return $this->values;
	}

	// returns a human readable label for the given value.
	// tries to figure it out, but you can override them by specifying them in 'labels' arg
	// for example, 'labels'=>['aux_life'=>'Auxiliary Life Membership'], (where "aux" is the value)
	// defaults to the currently selected value's option_label
	public function option_label($val=false){
		// default to current val
		if($val === false) $val = $this->val;

		if(isset($this->option_labels[$val])){
			return $this->option_labels[$val];
		}else{
			return ucwords(str_replace('_', ' ', $val));
		}
	}

	public function isnullable(){
		return $this->isnullable;
	}

	public function dbtype(){
		return 'enum(\''.implode('\',\'',$this->values).'\')';
	}

	public function null_label(){
		if($this->null_label){
			return $this->null_label;
		}else{
			return 'N/A';
		}
	}
}