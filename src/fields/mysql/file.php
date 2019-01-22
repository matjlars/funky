<?php
namespace funky\fields\mysql;

class file extends \funky\fields\base\file
{
	public function dbtype()
	{
		return 'varchar(255)';
	}
}