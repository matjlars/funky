<?php
namespace funky\controllers\admin\admin;

class configvars
{
	public function __construct()
	{
		f()->access->enforce('adminadmin');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		$vars = array();
		f()->load->view('admin/admin/configvars/index', array(
			'vars'=>$vars,
		));
	}
}
