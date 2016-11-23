<?php
namespace funky\fields;

class reference extends field
{
	public function init($args)
	{
		// default to 0
		$this->val = 0;
	}
	public function dbtype()
	{
		return 'int(11) unsigned';
	}
}
