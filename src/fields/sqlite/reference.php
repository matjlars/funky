<?php
namespace funky\fields\sqlite;

class reference extends \funky\fields\base\reference
{
	public function dbtype()
	{
		return 'integer';
	}
}
