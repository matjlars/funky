<?php
class adminadmin extends j_services
{
	public function isloggedin()
	{
		if(isset($_SESSION['isadminadmin']) && $_SESSION['isadminadmin'] == 1) return true;
		return false;
	}
	public function login($username, $password)
	{
		// if the username matches the one in the config:
		if($username == j()->config->adminadminusername){
			// if the password matches the one in the config:
			if(md5($password) == j()->config->adminadminpassword){
				$_SESSION['isadminadmin'] = 1;
			}
		}
	}
	public function logout()
	{
		unset($_SESSION['isadminadmin']);
	}
}