<?php
namespace funky\fields;

class integer extends field
{
	protected $length = 11;
	protected $signed = true;
	protected $bytes = 4;
	
	public function init($args)
	{
		if(isset($args['signed'])) $this->signed = $args['signed'];
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
		
		if(!empty($args['bytes'])){
			$this->bytes = $args['bytes'];
			$validbytes = array_keys(static::bytetypes());
			if(!in_array($this->bytes, $validbytes)){
				throw new \exception('a "bytes" value of "'.$this->bytes.'" is invalid. it must be one of ['.implode(', ', $validbytes).']');
			}
		}
	}
	public function dbtype()
	{
		$bytetypes = $this->bytetypes();
		$sql = $bytetypes[$this->bytes].'('.$this->length.')';
		if(!$this->signed){
			$sql .= ' unsigned';
		}
		return $sql;
	}
	protected static function bytetypes()
	{
		return array(
			1=>'tinyint',
			2=>'smallint',
			3=>'mediumint',
			4=>'int',
			8=>'bigint',
		);
	}
}