<?php
class index extends j_controller
{
	public function __construct()
	{
		j()->template->view = 'admin';
	}
	public function index()
	{
		if(!j()->access->isloggedin())
		{
			j()->path->redirect('admin/index/login');
		}
		
		// otherwise, we're logged in, so show a nice welcome thingy:
		j()->load->view('admin/index/index');
	}
	public function login()
	{
		$error = '';
		
		if(isset($_POST['username']) && isset($_POST['password'])) // handle normal log in requests
		{
			j()->access->login($_POST['username'], $_POST['password']);
			
			if(j()->access->isloggedin())
			{
				j()->path->redirect('admin');
			}
			else
			{
				$error = 'Unable to authenticate. Please try again.';
			}
		}
		
		// show the log in form:
		j()->load->view('admin/index/login',array(
			'error'=>$error,
		));
	}
	public function help()
	{
		j()->load->view('admin/index/help');
	}
	public function settings()
	{
		j()->load->view('admin/index/settings');
	}
	public function logout()
	{
		j()->session->clear();
		j()->path->redirect('admin/index/login');
	}
}