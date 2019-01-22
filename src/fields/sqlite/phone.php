<?php
namespace funky\fields\sqlite;

class phone extends \funky\fields\base\phone
{
	public function dbtype()
	{
		return 'text';
	}
}