<?php
class j_model
{
	private $data = array();
	protected $table = '';
	private static $fields = array();
	
	// $arg can be one of two things:
	// a) An array of data that will be inserted into db
	// b) An id that will be loaded from db
	public function __construct($arg=0)
	{
		$this->initcolumns();
		if(!empty($arg))
		{
			if(is_array($arg)) // this means we're inserting a new one
			{
				// insert a new row and grab the id
				$this->update($arg); // inserts and sets the id
				$arg = $this->id;
			}
			$this->data = j()->db->query('SELECT * FROM '.$this->table.' WHERE id='.$arg)->row();
		}
	}
	public function __get($name)
	{
		return $this->data[$name];
	}
	public function __set($name,$value)
	{
		$this->data[$name] = $value;
	}
	public function __isset($name)
	{
		return isset($this->data[$name]);
	}
	public function delete()
	{
		j()->db->query('DELETE FROM '.$this->table.' WHERE id='.$this->id);
	}
	public function save()
	{
		// strip the id out of the data, so it doesn't try to make it NULL
		$data = $this->data;
		unset($data['id']);
		
		if($this->exists())
		{
			j()->db->update($this->table,$data,'id',$this->id);
		}
		else // it doesn't exist yet, so insert:
		{
			$this->data['id'] = j()->db->insert($this->table,$data);
		}
	}
	
	// This function is a shortcut for stripping out all relevant fields passed in the $data, and calling $this->save()
	// it will also insert a new one if this one doesn't exist already
	public function update($data)
	{
		// update all the data and save:
		foreach($this->data as $key=>$value)
		{
			if(array_key_exists($key, $data)) $this->data[$key] = $data[$key];
		}
		$this->save();
	}
	
	public function initcolumns()
	{
		if(empty(self::$fields[$this->table]))
		{
			$columns = j()->db->query('SHOW COLUMNS FROM '.$this->table);
			
			self::$fields[$this->table] = array();
			foreach($columns as $col)
			{
				self::$fields[$this->table][] = $col['Field'];
			}
		}
		
		// Initialize all columns with empty strings:
		foreach(self::$fields[$this->table] as $field)
		{
			$this->data[$field] = '';
		}
	}
	
	public function dump()
	{
		echo '<pre>';
		print_r($this->data);
		echo '</pre>';
	}
	
	public function json()
	{
		return json_encode($this->data);
	}
	
	// returns TRUE if this model is already in the database, otherwise FALSE
	public function exists()
	{
		if(empty($this->data['id'])) return false;
		return true;
	}
}