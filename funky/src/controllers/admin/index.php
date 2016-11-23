<?php
namespace funky\controllers\admin;

class index
{
	public function __construct()
	{
		f()->template->view = 'admin';
	}
	public function index()
	{
		f()->access->enforce();
		
		// if I'm only an artisan, the only thing I can do is edit myself, so just go there if there is an artisan
		if(f()->access->user()->field('roles')->only('artisan')){
			$artisan_id = f()->db->query('select id from artisans where user_id = '.f()->access->user_id())->val('id');
			if(!empty($artisan_id)) f()->path->redirect('admin/artisans/edit/'.$artisan_id);
		}
		
		f()->load->view('admin/index/index');
	}
	public function login()
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
		f()->load->view('admin/index/login',array(
			'error'=>$error,
		));
	}
	public function logout()
	{
		f()->access->logout();
		f()->path->redirect('admin/index/login');
	}
}