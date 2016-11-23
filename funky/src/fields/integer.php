<?php
namespace funky\fields;

class integer extends field
{
	protected $length = 11;
	protected $signed = true;
	
	public function init($args)
	{
		if(!empty($args['length'])) $this->length = $args['length'];
		if(!empty($args['min'])){
			$this->validators[] = function($val){
				if($val < $args['min']) return 'less than '.$args['min'];
			};
		}
		if(!empty($args['max'])){
			$this->validators[] = function($val){
				if($val < $args['max']) return 'more than '.$args['max'];
			};
		}
		if(isset($args['signed'])){
			$this->signed = $args['signed'];
		}
	}
	public function dbtype()
	{
		$sql = 'int('.$this->length.')';
		if(!$this->signed){
			$sql .= ' unsigned';
		}
		return $sql;
	}
}