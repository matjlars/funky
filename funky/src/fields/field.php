<?php
namespace funky\fields;

class field
{
	protected $val = '';
	protected $validators;
	protected $errors;
	protected $name;
	protected $label;
	
	public function __construct($name, $args)
	{
		$this->name = $name;
		$this->init($args);
	}
	public function name()
	{
		return $this->name;
	}
	public function init($args)
	{
		if(!empty($args['label'])) $this->label = $args['label'];
	}
	public function get()
	{
		return $this->val;
	}
	// sets the value
	public function set($val)
	{
		$this->val = $val;
	}
	
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
	// validates this field exists in the database
	public function schemavalidate($table)
	{
		throw new \exception('TODO');
	}
}