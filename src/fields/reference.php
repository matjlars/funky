<?php
namespace funky\fields;

class reference extends \funky\fields\field
{
	// the name of the other model that this references
	protected $to = '';
	protected $tolabelfield = '';
	
	public function init($args)
	{
		// default to 0
		$this->val = 0;
		if(!empty($args['to'])) $this->to = $args['to'];
		if(!empty($args['tolabelfield'])) $this->tolabelfield = $args['tolabelfield'];
	}
	public function options()
	{
		// make sure we can call this function
		if(empty($this->to)) throw new \exception('you must specify a "to" arg for reference field '.$this->typename().' in order to use \fields\reference::options()');
		if(empty($this->tolabelfield)) throw new \exception('you must specify a "tolabelfield" arg for reference field '.$this->typename().' in order to use \fields\reference::options()');
		$otherclass = 'models\\'.$this->to;
		$othertable = $otherclass::table();
		return f()->db->query('select id,'.$this->tolabelfield.' from '.$othertable)->map('id', $this->tolabelfield);
	}

	public function dbtype()
	{
		return 'int(11) unsigned';
	}
}
