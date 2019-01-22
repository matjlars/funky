<?php
namespace funky\fields\mysql;

class decimal extends \funky\fields\base\decimal
{
	public function dbtype()
	{
		$total = $this->left + $this->right;
		return 'decimal('.$total.','.$this->right.')';
	}
}