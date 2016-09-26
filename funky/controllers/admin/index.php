<?php
class index extends f_controller
{
	public function __construct()
	{
		f()->template->view = 'admin';
	}
	public function index()
	{
		if(!f()->access->isloggedin())
		{
			f()->path->redirect('admin/index/login');
		}
		
		// otherwise, we're logged in, so show a nice welcome thingy:
		f()->load->view('admin/index/index');
	}
	public function login()
	{
		$error = '';
		
		if(isset($_POST['username']) && isset($_POST['password'])) // handle normal log in requests
		{
			f()->access->login($_POST['username'], $_POST['password']);
			
			if(f()->access->isloggedin())
			{
				f()->path->redirect('admin');
			}
			else
			{
				$error = 'Unable to authenticate. Please try again.';
			}
		}
		
		// show the log in form:
		f()->load->view('admin/index/login',array(
			'error'=>$error,
		));
	}
	public function help()
	{
		f()->load->view('admin/index/help');
	}
	public function settings()
	{
		f()->load->view('admin/index/settings');
	}
	public function logout()
	{
		f()->session->clear();
		f()->path->redirect('admin/index/login');
	}
}