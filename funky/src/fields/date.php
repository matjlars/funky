<?php
namespace funky\fields;

class date extends field
{
	// Accepts any date format that can be parsed by strtotime()
	public function set($val)
	{
		parent::set(strtotime($val));
	}
	public function get()
	{
		return $this->format('m/d/Y');
	}
	public function format($formatstring)
	{
		return \date($formatstring, $this->val);
	}
	public function time()
	{
		return $this->val;
	}
	public function dbval()
	{
		return $this->format('Y-m-d');
	}
	public function dbtype()
	{
		return 'date';
	}
}