<?php
namespace funky\core;

class model
{
	private $id = 0;
	private $fields = array();
	
	public function __construct()
	{
		// init fields:
		foreach(static::fields() as $field){
			$this->fields[$field->name()] = $field;
		}
	}
	public function __get($name)
	{
		if($name == 'id') return $this->id;
		if(!array_key_exists($name, $this->fields)) throw new \Exception('model '.get_called_class().' does not have field "'.$name.'"');
		return $this->fields[$name];
	}
	public function __set($name,$value)
	{
		$this->fields[$name]->set($value);
	}
	public function __isset($name)
	{
		return isset($this->fields[$name]);
	}
	public function delete()
	{
		if(empty($this->id)) throw new \exception('You cannot delete that which does not exist.');
		f()->db->query('DELETE FROM `'.static::table().'` WHERE id='.$this->id);
	}
	public function save()
	{
		if($this->exists()){
			f()->db->update(static::table(),$this->data(),'id',$this->id);
		}else{
			$this->id = f()->db->insert(static::table(),$this->data());
		}
	}
	public function data()
	{
		$data = array();
		foreach($this->fields as $name=>$field){
			$data[$name] = $field->dbval();
		}
		return $data;
	}
	
	// This function is a shortcut for stripping out all relevant fields passed in the $data, and calling $this->save()
	// it will also insert a new one if this one doesn't exist already
	public function update($data)
	{
		$this->setdata($data);
		if($this->isvalid()){
			$this->save();
		}
	}
	
	public function dump()
	{
		echo '<pre>';
		var_dump($this->data());
		echo '</pre>';
	}
	
	public function json()
	{
		return json_encode($this->data());
	}
	
	// returns TRUE if this model is already in the database, otherwise FALSE
	public function exists()
	{
		if(empty($this->id)) return false;
		return true;
	}
	
	public static function fields()
	{
		throw new \exception('You must implement fields() in all models');
	}
	public static function table()
	{
		$fullclass = get_called_class();
		$classname = substr($fullclass, strrpos($fullclass, '\\') + 1);
		return $classname.'s';
	}
	
	// takes an id and returns a single model object
	public static function fromid($id)
	{
		if(empty($id)) return new static();
		$sql = 'select * from `'.static::table().'` where id = '.f()->db->escape($id);
		$data = f()->db->query($sql)->row();
		if(empty($data['id'])) return new static();
		$obj = static::fromdata($data);
		$obj->id = $id;
		return $obj;
	}
	// takes an array of ids and returns an array of models
	public static function fromids($ids)
	{
		$results = array();
		$data = f()->db->query('select * from `'.static::table().'` where id IN ('.implode(',', $ids).')');
		foreach($data as $dat){
			$results[] = static::fromdata($dat);
		}
		return $results;
	}
	// takes an array of data and returns a single model object.
	// this function does not do anything with the db.
	public static function fromdata($data)
	{
		$obj = new static();
		$obj->setdata($data);
		if(isset($data['id'])) $obj->id = $data['id'];
		return $obj;
	}
	
	// takes an array of data, inserts it into the db, then returns the new model
	public static function insert($data)
	{
		if(isset($data['id'])) unset($data['id']);
		$obj = static::fromdata($data);
		$obj->save();
		return $obj;
	}
	// returns the number of these models in the database
	public static function count()
	{
		return f()->db->query('select count(1) as c from `'.static::table().'`')->val('c');
	}
	// returns a modelquery object to use to get an array of this type of model object
	// An example use case is like this: foreach(user::query()->where('name LIKE "%bob%"') as $user){$user->dostuff();}
	public static function query()
	{
		return new modelquery(get_called_class());
	}
	// runs all field validators and returns true or false, depending on if there were any errors
	public function isvalid()
	{
		$errors = $this->errors();
		return empty($errors);
	}
	// runs all field validators and returns an array of error messages
	public function errors()
	{
		$errors = array();
		foreach($this->fields as $field){
			foreach($field->errors() as $ferr){
				$errors[] = $field->label().' '.$ferr;
			}
		}
		return $errors;
	}
	// returns a string that is one long error message
	// it just combines all error messages from $this->errors() with the given delimiter
	// the default delimiter is ' and '
	public function errormessage($delim=' and ')
	{
		return implode($delim, $this->errors());
	}
	// takes an array of data and sets all applicable data
	private function setdata($data)
	{
		foreach($this->fields as $key=>$field){
			if(array_key_exists($key, $data)) $this->fields[$key]->set($data[$key]);
		}
	}
}