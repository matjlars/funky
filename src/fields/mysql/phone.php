<?php
namespace funky\fields\mysql;

class phone extends \funky\fields\base\phone
{
	public function dbtype()
	{
		return 'varchar(255)';
	}
}