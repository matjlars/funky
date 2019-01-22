<?php
namespace funky\fields\mysql;

class state extends \funky\fields\base\state
{
	public function dbtype()
	{
		return 'char(2)';
	}
}