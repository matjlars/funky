<?php
namespace funky\controllers\admin\admin;

class database
{
	public function __construct()
	{
		f()->access->enforce('dev');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}
	public function index()
	{
		return f()->view->load('admin/admin/database/index');
	}
	// ajax function that returns a table of all migrations
	public function migrations()
	{
		$migrations = f()->migrations->getall();
		return f()->view->load('admin/admin/database/migrations', array(
			'migrations'=>$migrations,
		));
	}
	// ajax endpoint that takes $_POST['sql'], runs it, and outputs some nice response HTML
	public function query()
	{
		if(empty($_POST['sql'])) die('<p>no sql given.</p>');
		try{
			if($_POST['action'] == 'query'){
				$result = f()->db->query($_POST['sql']);
			}elseif($_POST['action'] == 'exec'){
				$result = f()->db->exec($_POST['sql']);
			}else{
				throw new \Exception('need to either send "exec" or "query" in "action" key');
			}

			return f()->view->load('admin/admin/database/query', array(
				'result'=>$result,
			));
		}catch(\Exception $e){
			return f()->view->load('errors/message', array(
				'message'=>$e->getMessage(),
			));
		}
	}
}