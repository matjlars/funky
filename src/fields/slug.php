<?php
namespace funky\fields;

class slug extends \funky\fields\field
{
	private $slugify = false;

	public function set($val)
	{
		$val = static::sanitize($val);
		parent::set($val);
	}

	public function init($args){
		parent::init($args);
		if(!empty($args['slugify'])) $this->slugify = $args['slugify'];
	}

	public function get_slugify(){
		return $this->slugify;
	}

	// accepts anything that can be converted to a string
	// returns a slug-ified version of that string
	public static function sanitize($val)
	{
		// make sure it's a string
		$val = strval($val);
		
		// sanitize it
		$val = strtolower($val);
		$val = preg_replace('/[\']/', '', $val);
		$val = preg_replace('/[^a-z0-9-]/', '-', $val);
		$val = preg_replace('/-+/', '-', $val);
		$val = trim($val, '-');
		return $val;
	}

	public function dbtype()
	{
		return 'varchar(255)';
	}
}