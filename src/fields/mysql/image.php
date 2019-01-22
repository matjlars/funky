<?php
namespace funky\fields\mysql;

use models\image as imagemodel;

// $this->val is the image_id
class image extends \funky\fields\base\image
{
	public function dbtype()
	{
		return 'int(11) unsigned';
	}
}