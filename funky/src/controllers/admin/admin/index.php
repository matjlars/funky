<?php
namespace funky\controllers\admin\admin;

class index
{
	public function __construct()
	{
		f()->access->enforce('adminadmin');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		f()->load->view('admin/admin/index/index');
	}
}