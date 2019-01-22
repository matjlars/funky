<?php
namespace funky\fields\mysql;

class enum extends \funky\fields\base\enum
{
	public function dbtype()
	{
		return 'enum(\''.implode('\',\'',$this->values).'\')';
	}
}