<?php
namespace funky\controllers\admin;

class logout{
	public function index(){
		f()->access->logout();
		f()->response->redirect('/admin/login');
	}
}
