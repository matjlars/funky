<?php
namespace funky\controllers\admin;

class login{
	public function __construct(){
		f()->template->view = 'admin';
	}

	public function index(){
		$error = '';

		// handle normal log in requests
		if(isset($_POST['email']) && isset($_POST['password'])){
			f()->access->login($_POST['email'], $_POST['password']);
			
			if(f()->access->isloggedin()){
				f()->response->redirect('/admin');
			}else{
				$error = 'Unable to authenticate. Please try again.';
			}
		}
		
		// show the log in form:
		return f()->view->load('admin/login/index', [
			'error'=>$error,
		]);
	}
}