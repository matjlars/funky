<?php
namespace funky\fields;

class phone extends field
{
	public function set($val)
	{
		$val = static::sanitize($val);
		parent::set($val);
	}
	public function tel()
	{
		return '+'.$this->val;
	}
	public function formatted()
	{
		$len = strlen($this->val);
		if($len == 7){
			$first = substr($this->val, 0, 3);
			$second = substr($this->val, 3);
			return $first.'-'.$second;
		}else if($len == 10){
			$first = substr($this->val, 0, 3);
			$second = substr($this->val, 3, 3);
			$third = substr($this->val, 6);
			return '('.$first.') '.$second.'-'.$third;
		}
		return $this->val;
	}
	public function dbtype()
	{
		return 'varchar(255)';
	}
	public static function sanitize($val)
	{
		// strip all non-numeric characters out:
		return preg_replace('/[^0-9]/', '', $val);
	}
}