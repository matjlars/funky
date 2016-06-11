<?php
class access extends j_module
{
	private $user = NULL;
	
	public function user()
	{
		if($this->user === NULL)
		{
			if(!empty($_SESSION['user_id']))
			{
				$this->user = new user($_SESSION['user_id']);
			}
		}
		return $this->user;
	}
	public function login($username,$password)
	{
		$user = j()->db->query('SELECT id FROM users WHERE username LIKE "'.$username.'" AND password = "'.md5($password).'"')->row();
		if(!empty($user['id']))
		{
			$_SESSION['user_id'] = $user['id'];
			j()->db->query('UPDATE users SET ip = "'.$_SERVER['REMOTE_ADDR'].'" WHERE id = '.$user['id']);
			$this->user = j()->db->query('SELECT * FROM users WHERE id = '.$user['id'])->row();
			return true; // success!
		}
		return false; // login failed
	}
	public function logout()
	{
		unset($_SESSION['user_id']);
	}
	public function isloggedin()
	{
		if(empty($_SESSION['user_id'])) return false;
		return true;
	}
	public function enforce()
	{
		if(!$this->isloggedin())
		{
			$loginpage = 'login.php';
			if(!empty(j()->config->loginpage)) $loginpage = j()->config->loginpage;
			if(!$this->isloggedin()) j()->path->redirect($loginpage);
		}
	}
}