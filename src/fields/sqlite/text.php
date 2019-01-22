<?php
namespace funky\fields\sqlite;

class text extends \funky\fields\base\text
{
	public function dbtype()
	{
		return 'text';
	}
}
