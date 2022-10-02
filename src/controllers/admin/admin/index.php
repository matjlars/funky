<?php
namespace funky\controllers\admin\admin;

class index{
	public function __construct(){
		f()->access->enforce('dev');
		f()->template->view = 'admin';
	}

	public function index(){
		return f()->view->load('admin/admin/index/index');
	}
}