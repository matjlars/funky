<?php
namespace funky\fields\mysql;

class integer extends \funky\fields\base\integer
{
	public function dbtype()
	{
		$sql = 'int ('.$this->length.')';
		if(!$this->signed) $sql .= ' unsigned';
		return $sql;
	}
}