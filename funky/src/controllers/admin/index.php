<?php
namespace funky\controllers\admin;

class index
{
	public function __construct()
	{
		f()->access->enforce();
		f()->template->view = 'admin';
	}
	public function index()
	{
		f()->load->view('admin/index/index');
	}
}