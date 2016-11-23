<?php
use \models\user;

class access
{
	private $user = null;
	
	public function isloggedin()
	{
		$user_id = $this->user_id();
		return !empty($user_id);
	}
	public function login($email, $password)
	{
		// see if this user is in the database:
		$user_id = f()->db->query('select id from users where email = "'.f()->db->escape($email).'" AND password = "'.md5($password).'"')->val('id');
		
		// if this user_id exists, log the user in:
		if(!empty($user_id)){
			f()->session->user_id = $user_id;
			$this->user = null;
		}
	}
	public function logout()
	{
		unset(f()->session->user_id);
		$this->user = null;
	}
	// automatically redirects you to the login path if you are not logged in
	// additionally, if any roles are specified (a single string or an array of strings), it makes sure the user has at least one of the given roles
	public function enforce($roles=array())
	{
		// if we're not logged in at all, redirect to path:
		if(!$this->isloggedin()){
			f()->path->redirect('admin/index/login');
		}
		
		// in this context, we are logged in. check roles if any given:
		if(!empty($roles)){
			if(is_string($roles)) $roles = array($roles);
			foreach($roles as $role){
				if($this->hasrole($role)){
					return;
				}
			}
			f()->path->redirect('admin/index/login');
		}
	}
	public function user()
	{
		if(!$this->isloggedin()) return new user();
		if($this->user === null) $this->user = user::fromid($this->user_id());
		return $this->user;
	}
	public function hasrole($role)
	{
		return $this->user()->hasrole($role);
	}
	public function user_id()
	{
		if(isset(f()->session->user_id)) return f()->session->user_id;
		return 0;
	}
}