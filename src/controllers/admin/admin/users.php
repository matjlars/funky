<?php
namespace funky\controllers\admin\admin;

use models\user;

class users
{
	public function __construct()
	{
		f()->access->enforce('dev');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		// get all users
		$users = user::query();
		return f()->view->load('admin/admin/users/index', array(
			'users'=>$users,
		));
	}
	public function edit($id=0)
	{
		$user = user::fromid($id);
		if(!empty($_POST)){
			// get rid of the password if it's blank.
			// this will prevent saving the password as a hash of the empty string
			if(empty($_POST['password'])) unset($_POST['password']);
			$user->update($_POST);
			if($user->isvalid()){
				f()->response->redirect('/admin/admin/users');
			}
		}
		return f()->view->load('admin/admin/users/edit', array(
			'user'=>$user,
		));
	}
	public function delete($id=0)
	{
		$user = user::fromid($id);
		$user->delete();
		f()->response->redirect('/admin/admin/users');
	}
}