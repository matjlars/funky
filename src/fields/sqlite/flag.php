<?php
namespace funky\fields\sqlite;

class flag extends \funky\fields\base\flag
{
	public function dbtype()
	{
		return 'integer';
	}
}