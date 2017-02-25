<?php
namespace funky\services;

use models\user;

class access
{
	private $user = null;
	
	public function isloggedin()
	{
		// load the user so it tests to make sure we're logged in as a valid user
		$user_id = $this->user()->id;
		if(empty($user_id)) return false;
		return true;
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
	public function enforce($roles=array(), $loginpath='/admin/login')
	{
		// if we're not logged in at all, redirect to path:
		if(empty($this->user_id())){
			f()->path->redirect($loginpath);
		}
		
		// in this context, we are logged in. check roles if any given:
		if(!empty($roles)){
			if(is_string($roles)) $roles = array($roles);
			foreach($roles as $role){
				if($this->hasrole($role)){
					return;
				}
			}
			f()->path->redirect($loginpath);
		}
	}
	public function user()
	{
		if(empty($this->user_id())) return new user();
		if($this->user === null) $this->user = user::fromid($this->user_id());
		// if this user model doesn't exist, log us out right here.
		if(!$this->user->exists()){
			$this->logout();
			return new user();
		}
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