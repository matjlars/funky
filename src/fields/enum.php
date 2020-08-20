<?php
namespace funky\fields;

class enum extends \funky\fields\field
{
	private $values = array();
	private $isnullable = true;
	
	public function init($args)
	{
		if(empty($args['values'])) throw new \exception('enum field '.$this->name.' requires a "values" arg. this should contain strings that are the enum keys in the database');
		if(!is_array($args['values'])) throw new \exception('enum field '.$this->name.' has a "values" arg, but it is not an array. It should be an array of strings for the enum keys');
		$this->values = $args['values'];

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

	public function set($val)
	{
		if(empty($val) && $this->isnullable){
			$val = null;
		}
		parent::set($val);
	}

	public function values()
	{
		return $this->values;
	}

	public function isnullable(){
		return $this->isnullable;
	}

	public function dbtype()
	{
		return 'enum(\''.implode('\',\'',$this->values).'\')';
	}
}