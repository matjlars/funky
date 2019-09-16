<?php
namespace funky\fields;

class money extends \funky\fields\field
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
