<?php
namespace funky\fields;

class time extends \funky\fields\field
{
	private $format;

	public function init($args)
	{
		// set the default format:
		if(empty($args['format'])){
			$this->format = 'h:i A';
		}else{
			$this->format = $args['format'];
		}

		if(isset($args['default']) && $args['default'] == 'now') $this->val = time();
	}

	public function set($val)
	{
		parent::set(strtotime($val));
	}

	public function get()
	{
		return $this->format($this->format);
	}

	public function format($format)
	{
		return \date($format, $this->val);
	}

	public function dbval()
	{
		return $this->format('H:i:s');
	}

	public function dbtype()
	{
		return 'time';
	}
}