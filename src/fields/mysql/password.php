<?php
namespace funky\fields\mysql;

class password extends \funky\fields\base\password
{
	public function dbtype()
	{
		return 'char(32)';
	}
}