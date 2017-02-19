<?php
namespace funky\fields;

class slug extends text
{
	public function set($val)
	{
		$val = static::sanitize($val);
		parent::set($val);
	}
	// accepts anything that can be converted to a string
	// returns a slug-ified version of that string
	public static function sanitize($val)
	{
		// make sure it's a string
		$val = strval($val);
		
		// sanitize it
		$val = strtolower($val);
		$val = preg_replace('/[\']/', '', $val);
		$val = preg_replace('/[^a-z0-9-]/', '-', $val);
		$val = preg_replace('/-+/', '-', $val);
		return $val;
	}
}