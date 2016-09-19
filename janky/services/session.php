<?php
class session extends j_service
{
	private $started = false;
	
	public function get($key)
	{
		$this->start();
		return $_SESSION[$key];
	}
	public function set($key, $value)
	{
		$this->start();
		$_SESSION[$key] = $value;
	}
	public function clear()
	{
		// clear all session vars:
		session_unset();
	}
	public function start()
	{
		if($this->started === false){
			session_start();
			$this->started = true;
		}
	}
}