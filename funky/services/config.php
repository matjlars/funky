<?php
class config
{
	private $data;
	public function __construct()
	{
		// Load $data array from config file:
		include f()->path->php('config.php');
		if(isset($data)) $this->data = $data;
	}
	public function __set($key,$value)
	{
		$this->data[$key] = $value;
	}
	public function __get($key)
	{
		if(isset($this->data[$key])) return $this->data[$key];
		else throw new exception('Config value for key "'.$key.'" not found.');
	}
	public function __isset($key)
	{
		return isset($this->data[$key]);
	}
}