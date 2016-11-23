<?php
namespace funky\controllers\admin\admin;

class database
{
	public function __construct()
	{
		f()->access->enforce('adminadmin');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		f()->load->view('admin/admin/database/index');
	}
	// ajax function that returns a table of all migrations
	public function migrations()
	{
		$migrations = f()->migrations->getall();
		f()->load->view('admin/admin/database/migrations', array(
			'migrations'=>$migrations,
		));
	}
	// ajax endpoint that takes $_POST['sql'], runs it, and outputs some nice response HTML
	public function query()
	{
		if(empty($_POST['sql'])) die('<p>no sql given.</p>');
		try{
			$result = f()->db->query($_POST['sql']);
			f()->load->view('admin/admin/database/query', array(
				'result'=>$result,
			));
		}catch(\exception $e){
			f()->load->view('errors/message', array(
				'message'=>$e->getMessage(),
			));
		}
	}
}