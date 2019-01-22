<?php
namespace funky\fields\sqlite;

class decimal extends \funky\fields\base\decimal
{
	public function dbtype()
	{
		return 'text';
	}
}