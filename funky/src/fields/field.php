<?php
namespace funky\fields;

class field
{
	protected $val = '';
	protected $validators;
	protected $errors;
	protected $name;
	protected $label;
	
	public function __construct($name, $args=array())
	{
		$this->name = $name;
		if(!empty($args['label'])) $this->label = $args['label'];
		$this->init($args);
	}
	public function name()
	{
		return $this->name;
	}
	
	// override this function to do stuff with args and set up anything else
	public function init($args){}
	
	// returns a user-readable value for this field
	public function get()
	{
		return $this->val;
	}
	// sets the value
	public function set($val)
	{
		$this->val = $val;
	}
	
	// returns a user-readable label for this field
	public function label()
	{
		if(empty($this->label)){
			return ucwords($this->name);
		}else{
			return $this->label;
		}
	}
	public function setlabel($label)
	{
		$this->label = $label;
	}
	// runs all validators
	public function validate()
	{
		foreach($validators as $validator){
			$v = $validator($this->val);
			if($v != null) $this->errors[] = $v;
		}
		return empty($this->errors);
	}
	public function view($view='')
	{
		if(empty($view)) $view = 'view';
		$view = 'fields/'.$this->typename().'/'.$view;
		f()->load->view($view, array(
			'field'=>$this,
		));
	}
	public function typename()
	{
		return gettype($this);
	}
	// returns a value that can be saved to the database
	public function dbval()
	{
		return $this->val;
	}
	// returns the sql needed to make this field exist in the db schema
	public function dbtype()
	{
		throw new \exception('TODO override '.$this->typename().'->dbtype()');
	}
}