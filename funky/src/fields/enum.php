<?php
namespace funky\fields;

class enum extends field
{
	private $values = array();
	
	public function init($args)
	{
		if(empty($args['values'])) throw new \exception('enum field '.$this->name.' requires a "values" arg. this should contain strings that are the enum keys in the database');
		if(!is_array($args['values'])) throw new \exception('enum field '.$this->name.' has a "values" arg, but it is not an array. It should be an array of strings for the enum keys');
		$this->values = $args['values'];
		
		// validate the value
		$this->validators[] = function($val){
			if(empty($val)) return;
			if(!in_array($val, $this->values)) return 'contains an invalid value ('.$val.'). It should be one of ['.implode(',',$this->values).']';
		};
	}
	public function values()
	{
		return $this->values;
	}
	public function dbtype()
	{
		return 'enum(\''.implode('\',\'',$this->values).'\')';
	}
}