<?php
namespace funky\fields\base;

abstract class flag extends \funky\fields\field
{
	public function set($val)
	{
		if($val) $this->val = true;
		else $this->val = false;
	}
	public function dbval()
	{
		if($this->val) return 1;
		return 0;
	}
}