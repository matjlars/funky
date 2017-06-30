<?php
namespace funky\fields;

class money extends decimal
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
