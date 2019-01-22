<?php
namespace funky\fields\sqlite;

class image extends \funky\fields\base\image
{
	public function dbtype()
	{
		return 'integer';
	}
}