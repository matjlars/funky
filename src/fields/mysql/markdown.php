<?php
namespace funky\fields\mysql;

class markdown extends \funky\fields\base\markdown
{
	public function dbtype()
	{
		return 'text';
	}
}