<?php
namespace funky\fields\sqlite;

class markdown extends \funky\fields\base\markdown
{
	public function dbtype()
	{
		return 'text';
	}
}