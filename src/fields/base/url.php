<?php
namespace funky\fields\base;

abstract class url extends \funky\fields\field
{
	public function set($val)
	{
		$val = static::sanitize($val);
		parent::set($val);
	}
	public static function sanitize($val)
	{
		if(empty($val)) return '';
		
		// if there is no http: or https: add https:
		if(substr($val, 0, 4) != 'http'){
			$val = 'https://'.$val;
		}
		return $val;
	}
}