<?php
namespace funky\fields;

class text extends field
{
	protected $minlength = 0;
	protected $length = 255;
	
	public function init($args)
	{
		if(!empty($args['length'])){
			$this->max = $args['length'];
		}
		// check length
		$this->validators[] = function($val){
			if(strlen($val) > $this->length) return 'exceeds max length of '.$this->length.' by '.(strlen($val)-$this->length).' characters';
		};
	}
	public function dbtype()
	{
		$sqltype = 'varchar';
		if($this->length == $this->minlength){
			$sqltype = 'char';
		}else{
			if($this->length <= 255){
				$sqltype = 'varchar';
			}else if($this->length < 4096){
				$sqltype = 'text';
			}else{
				throw new \exception('TODO add text type for length '.$this->length);
			}
		}
		return $sqltype.'('.$this->length.')';
	}
}
