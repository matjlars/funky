<?php
namespace funky\fields\sqlite;

class state extends \funky\fields\base\state
{
	public function dbtype()
	{
		return 'text';
	}
}