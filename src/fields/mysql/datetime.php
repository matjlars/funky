<?php
namespace funky\fields\mysql;

class datetime extends \funky\fields\base\datetime
{
	public function dbtype()
	{
		return 'datetime';
	}
}
