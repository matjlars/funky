<?php
namespace funky\fields\mysql;

class set extends \funky\fields\base\set
{
	public function dbtype()
	{
		// make a sql string of all the values with single quotes
		$escapedvals = array();
		foreach($this->values as $val){
			$escapedvals[] = "'$val'";
		}
		return 'set('.implode(',', $escapedvals).')';
	}
}