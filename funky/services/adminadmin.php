<?php
class adminadmin
{
	public function isloggedin()
	{
		if(isset(j()->session->isadminadmin) && j()->session->isadminadmin == 1) return true;
		return false;
	}
	public function login($username, $password)
	{
		// if the username matches the one in the config:
		if($username == j()->config->adminadminusername){
			// if the password matches the one in the config:
			if(md5($password) == j()->config->adminadminpassword){
				j()->session->isadminadmin = 1;
			}
		}
	}
	public function logout()
	{
		unset(j()->session->isadminadmin);
	}
}