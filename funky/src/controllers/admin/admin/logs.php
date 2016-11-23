<?php
namespace funky\controllers\admin\admin;

class logs
{
	public function __construct()
	{
		f()->access->enforce('adminadmin');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		$logs = array();
		f()->load->view('admin/admin/logs/index', array(
			'logs'=>$logs,
		));
	}
}
