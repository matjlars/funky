<?php
namespace funky\fields;

class password extends field
{
	public function init($args)
	{
		parent::init($args);
	}
	public function dbtype()
	{
		return 'char(32)';
	}
}