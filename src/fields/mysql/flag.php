<?php
namespace funky\fields\mysql;

class flag extends \funky\fields\base\flag
{
	public function dbtype()
	{
		return 'bit(1)';
	}
}