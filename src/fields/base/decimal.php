<?php
namespace funky\fields\base;

abstract class decimal extends \funky\fields\field
{
	// how many digits are to the left of the decimal
	protected $left = 6;
	
	// how many digits are to the right of the decimal
	protected $right = 2;

	// the max value
	protected $max;
	
	public function init($args)
	{
		if(isset($args['left'])) $this->left = $args['left'];
		if(isset($args['right'])) $this->right = $args['right'];
		if(isset($args['max'])){
			$this->max = $args['max'];
		}else{
			// calculate max:
			$this->max = str_repeat('9', $this->left).'.'.str_repeat('9', $this->right);
		}

		// set the default:
		if(isset($args['default'])){
			$this->val = static::sanitize($args['default']);
		}else{
			$this->val = '0.0';
		}
		
		// validate the left length
		$this->validators[] = function($val){
			$tokens = explode('.', $val);
			$left = strlen($tokens[0]);
			if($left > $this->left) return 'exceeds maximum value of '.$this->max;
		};
	}

	public function set($val)
	{
		$val = static::sanitize($val);
		parent::set($val);
	}

	public function __toString()
	{
		return $this->val;
	}

	// accepts anything that can be converted to a string
	// outputs a string that is sanitized as a decimal string
	public static function sanitize($val)
	{
		// make sure it's a string
		$val = strval($val);
		// get location of period
		$period = strpos($val, '.');
		if($period === false){
			$left = $val;
			$right = '0';
		}else{
			$left = substr($val, 0, $period);
			$right = substr($val, $period+1);
		}
		
		// make sure there's nothing but numbers
		$left = preg_replace('/[^0-9]/', '', $left);
		$right = preg_replace('/[^0-9]/', '', $right);
		
		// piece it together:
		return $left.'.'.$right;
	}
}