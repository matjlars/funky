<?php
namespace funky\controllers\admin\admin;

class logs
{
	public function __construct()
	{
		f()->access->enforce('dev');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		$logs = array();
		return f()->view->load('admin/admin/logs/index', array(
			'logs'=>$logs,
		));
	}
}
