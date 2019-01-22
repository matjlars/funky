<?php
namespace funky\fields\sqlite;

class datetime extends \funky\fields\base\datetime
{
	public function dbtype()
	{
		return 'text';
	}
}
