<?php
namespace funky\fields\base;

abstract class markdown extends \funky\fields\field
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

	public function __toString()
	{
		return $this->render();
	}
}