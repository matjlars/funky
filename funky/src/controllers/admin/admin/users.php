<?php
namespace funky\controllers\admin\admin;

use models\user;

class users
{
	public function __construct()
	{
		f()->access->enforce('adminadmin');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		// get all users
		$users = user::query();
		f()->load->view('admin/admin/users/index', array(
			'users'=>$users,
		));
	}
	public function edit($id=0)
	{
		$user = user::fromid($id);
		if(!empty($_POST)){
			$user->email = $_POST['user']['email'];
			$user->roles = implode(',', $_POST['user']['roles']);
			if(!empty($_POST['user']['password'])) $user->set_password($_POST['user']['password']);
			$user->save();
			f()->path->redirect('/admin/admin/users');
		}else{
			f()->load->view('admin/admin/users/edit', array(
				'user'=>$user,
			));
		}
	}
	public function delete($id=0)
	{
		$user = user::fromid($id);
		$user->delete();
		f()->path->redirect('admin/admin/users');
	}
}