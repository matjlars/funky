<?php
namespace funky\controllers\admin\admin;

class validator
{
	public function __construct()
	{
		f()->access->enforce('dev');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	
	public function index()
	{
		return f()->view->load('admin/admin/validator/index');
	}
}