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
	// accepts a PHP date() format string and returns this date in that format
	// google "php date" if you want all the formatting options
	public function format($formatstring)
	{
		// if we have no time, don't try to make a date
		if(empty($this->val)) return '';
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