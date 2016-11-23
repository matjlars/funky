<?php
class session
{
	public function __get($key)
	{
		if(!isset($_SESSION[$key])) throw new exception('no session var "'.$key.'"');
		return $_SESSION[$key];
	}
	public function __set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	public function __isset($key)
	{
		return isset($_SESSION[$key]);
	}
	public function clear()
	{
		session_unset();
	}
	public function __unset($key)
	{
		unset($_SESSION[$key]);
	}
}