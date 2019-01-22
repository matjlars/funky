<?php
namespace funky\fields\base;

abstract class text extends \funky\fields\field
{
	protected $minlength = 0;
	protected $length = 255;
	
	public function set($val)
	{
		$val = trim($val);
		parent::set($val);
	}
	public function init($args)
	{
		// set length
		if(!empty($args['length'])){
			if(is_numeric($args['length'])){
				$this->length = $args['length'];
			}else{
				$englishmaxes = [
					'short'=>255,
					'medium'=>4096,
				];
				// make sure a correct key is given
				if(!array_key_exists($args['length'], $englishmaxes)){
					throw new \Exception('The text field\'s length arg requires a number of characters or one of ['.implode(', ', $englishmaxes).']');
				}
				$this->length = $englishmaxes[$args['length']];
			}
		}
		if(isset($args['minlength'])) $this->minlength = $args['minlength'];
		// validate length
		$this->validators[] = function($val){
			if(strlen($val) > $this->length) return 'exceeds max length of '.$this->length.' by '.(strlen($val)-$this->length).' characters';
		};
	}
}
