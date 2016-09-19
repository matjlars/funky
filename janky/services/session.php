<?php
class session extends j_service
{
	public function __construct()
	{
		session_start();
	}
	public function get($key)
	{
		return $_SESSION[$key];
	}
	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	public function isset($key)
	{
		return isset($_SESSION[$key]);
	}
	public function empty($key)
	{
		return empty($_SESSION[$key]);
	}
	public function clear()
	{
		session_unset();
	}
	public function unset($key)
	{
		unset($_SESSION[$key]);
	}
}