<?php
namespace funky\fields\mysql;

class time extends \funky\fields\base\time
{
	public function dbtype()
	{
		return 'time';
	}
}