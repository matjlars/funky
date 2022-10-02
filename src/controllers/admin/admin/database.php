<?php
namespace funky\controllers\admin\admin;

class database{
	public function __construct(){
		f()->access->enforce('dev');
		f()->template->view = 'admin';
	}

	public function index(){
		return f()->view->load('admin/admin/database/index');
	}

	// ajax function that returns a table of all migrations
	public function migrations(){
		$migrations = f()->migrations->getall();
		return f()->view->load('admin/admin/database/migrations', [
			'migrations'=>$migrations,
		]);
	}

	// ajax endpoint that takes $_POST['sql'], runs it, and outputs some nice response HTML
	public function query(){
		if(empty($_POST['sql'])) die('<p>no sql given.</p>');

		try{
			$result = f()->db->query($_POST['sql']);
			return f()->view->load('admin/admin/database/query', [
				'result'=>$result,
			]);
		}catch(\Exception $e){
			return f()->view->load('errors/message', [
				'message'=>$e->getMessage(),
			]);
		}
	}
}