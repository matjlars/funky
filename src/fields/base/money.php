<?php
namespace funky\fields\base;

abstract class money extends \funky\fields\field
{
	public function get()
	{
		return '$'.$this->val;
	}
	public function __toString()
	{
		return $this->get();
	}
}
