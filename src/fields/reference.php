<?php
namespace funky\fields;

class reference extends \funky\fields\field{
	// the name of the other model that this references
	protected $to = '';
	protected $tolabelfield = '';
	protected $options_order_by = null;
	protected $options_order_dir = null;
	
	public function init($args){
		// default to 0
		$this->val = 0;

		if(!empty($args['to'])) $this->to = $args['to'];
		if(!empty($args['tolabelfield'])){
			$this->tolabelfield = $args['tolabelfield'];
			$this->options_order_by = $args['tolabelfield'];
		}

		// allow overriding options order by and dir
		if(!empty($args['options_order_by'])) $this->options_order_by = $args['options_order_by'];
		if(!empty($args['options_order_dir'])) $this->options_order_dir = $args['options_order_dir'];
	}

	public function options(){
		// make sure we can call this function
		if(empty($this->to)) throw new \exception('you must specify a "to" arg for reference field '.$this->typename().' in order to use \fields\reference::options()');
		if(empty($this->tolabelfield)) throw new \exception('you must specify a "tolabelfield" arg for reference field '.$this->typename().' in order to use \fields\reference::options()');
		$otherclass = 'models\\'.$this->to;
		$othertable = $otherclass::table();
		$sql = 'select id,'.$this->tolabelfield.' from `'.$othertable.'`';

		// order the options
		if(!empty($this->options_order_by)){
			$sql .= ' ORDER BY '.$this->options_order_by;
			if(!empty($this->options_order_dir)){
				$sql .= ' '.$this->options_order_dir;
			}
		}

		return f()->db->query($sql)->map('id', $this->tolabelfield);
	}

	public function set($val){
		$this->val = intval($val);
	}

	public function dbtype(){
		return 'int unsigned';
	}
}
