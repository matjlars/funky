<?php
namespace funky\controllers\admin;

class login
{
	public function __construct()
	{
		f()->template->view = 'admin';
	}
	public function index()
	{
		$error = '';
		
		if(isset($_POST['email']) && isset($_POST['password'])) // handle normal log in requests
		{
			f()->access->login($_POST['email'], $_POST['password']);
			
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
		f()->load->view('admin/login/index',array(
			'error'=>$error,
		));
	}
}