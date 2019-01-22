<?php
namespace funky\fields\sqlite;

class password extends \funky\fields\base\password
{
	public function dbtype()
	{
		return 'text';
	}
}