<?php
namespace funky\fields;

class markdown extends field
{
	public function set($val)
	{
		$val = trim($val);
		parent::set($val);
	}

	public function render()
	{
		return f()->markdown->render($this->val);
	}

	public function dbtype()
	{
		return 'text';
	}

	public function __toString()
	{
		return $this->render();
	}
}