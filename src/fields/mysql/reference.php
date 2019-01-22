<?php
namespace funky\fields\mysql;

class reference extends \funky\fields\base\reference
{
	public function dbtype()
	{
		return 'int(11) unsigned';
	}
}
