<?php
class access
{
	public function isloggedin()
	{
		return !empty(j()->session->user_id);
	}
	public function issuperuser()
	{
		return false;
	}
	public function login($email, $password)
	{
		// see if this user is in the database:
		$user_id = f()->db->query('select id from users where email = "'.mysql_real_escape_string($email).'" AND password = '.md5($password))->val('id');
		// TODO check this user_id
	}
}