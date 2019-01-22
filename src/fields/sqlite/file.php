<?php
namespace funky\fields\sqlite;

class file extends \funky\fields\base\file
{
	public function dbtype()
	{
		return 'text';
	}
}