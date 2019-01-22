<?php
namespace funky\fields\mysql;

class date extends \funky\fields\base\date
{
	public function dbtype()
	{
		return 'date';
	}
}