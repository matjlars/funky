<?php
namespace funky\fields;

class reference extends \funky\fields\field
{
	// the name of the other model that this references
	protected $to = '';
	protected $tolabelfield = '';
	protected $options_order = '';
	
	public function init($args)
	{
		// default to 0
		$this->val = 0;
		if(!empty($args['to'])) $this->to = $args['to'];
		if(!empty($args['tolabelfield'])) $this->tolabelfield = $args['tolabelfield'];
		if(!empty($args['options_order'])) $this->options_order = $args['options_order'];
	}
	public function options()
	{
		// make sure we can call this function
		if(empty($this->to)) throw new \exception('you must specify a "to" arg for reference field '.$this->typename().' in order to use \fields\reference::options()');
		if(empty($this->tolabelfield)) throw new \exception('you must specify a "tolabelfield" arg for reference field '.$this->typename().' in order to use \fields\reference::options()');
		$otherclass = 'models\\'.$this->to;
		$othertable = $otherclass::table();

		$sql = 'select id,'.$this->tolabelfield.' from '.$othertable.' ORDER BY ';
		if(empty($this->options_order)){
			$sql .= $this->tolabelfield;
		}else{
			$sql .= $this->options_order;
		}
		return f()->db->query($sql)->map('id', $this->tolabelfield);
	}

	public function dbtype()
	{
		return 'int unsigned';
	}
}
