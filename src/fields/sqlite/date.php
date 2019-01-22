<?php
namespace funky\fields\sqlite;

class date extends \funky\fields\base\date
{
	public function dbtype()
	{
		return 'text';
	}
}