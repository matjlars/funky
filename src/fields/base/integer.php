<?php
namespace funky\fields\base;

abstract class integer extends \funky\fields\field
{
	protected $length = 11;
	protected $signed = true;
	
	public function init($args)
	{
		// set the default:
		if(isset($args['default'])){
			$this->val = $args['default'];
		}else{
			$this->val = 0;
		}

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
	}
}