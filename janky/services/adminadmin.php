<?php
class adminadmin extends j_services
{
	public function isloggedin()
	{
		if(j()->session->isset('isadminadmin') && j()->session->get('isadminadmin') == 1) return true;
		return false;
	}
	public function login($username, $password)
	{
		// if the username matches the one in the config:
		if($username == j()->config->adminadminusername){
			// if the password matches the one in the config:
			if(md5($password) == j()->config->adminadminpassword){
				j()->session->set('isadminadmin', 1);
			}
		}
	}
	public function logout()
	{
		j()->session->unset('isadminadmin');
	}
}