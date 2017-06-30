<?php
namespace funky\fields;

class field
{
	protected $val = '';
	protected $validators = array();
	protected $name;
	protected $label;
	
	public function __construct($name, $args=array())
	{
		$this->name = $name;
		if(!empty($args['label'])) $this->label = $args['label'];
		if(!empty($args['default'])) $this->val = $args['default'];
		$this->init($args);
	}
	public function name()
	{
		return $this->name;
	}
	
	// override this function to do stuff with args and set up anything else
	public function init($args){}
	
	// returns a user-readable value for this field
	// override this if you can make a more user-readable version of the data for your field
	public function get()
	{
		return $this->val;
	}
	// sets the value
	// override this if you want to be able to do some cleaning or other logic whenever this value is set
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
	// runs all validators and returns an array of errors
	public function errors()
	{
		$errors = array();
		foreach($this->validators as $validator){
			$error = $validator($this->val);
			if($error != null) $errors[] = $error;
		}
		return $errors;
	}
	// returns a string containing the content of the field view
	// these views are relative to /views/fields/FIELD_CLASS/
	// the view file will get passed the "field" variable containing the field object
	public function view($view='')
	{
		if(empty($view)) $view = 'view';
		$view = 'fields/'.$this->typename().'/'.$view;
		return f()->view->load($view, array(
			'field'=>$this,
		));
	}
	public function typename()
	{
		$classname = get_called_class();
		$startpos = strrpos($classname, '\\');
		$classname = substr($classname, $startpos+1);
		return $classname;
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
	// returns a string representing this value as printed in the html
	// this way, you can just output $model->fieldname in form fields
	// obviously, this is only really relevant to simple input fields with "value" attributes
	public function __toString()
	{
		return strval($this->dbval());
	}
}