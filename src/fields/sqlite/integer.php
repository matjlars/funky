<?php
namespace funky\fields\sqlite;

class integer extends \funky\fields\base\integer
{
	public function dbtype()
	{
		return 'integer';
	}
}