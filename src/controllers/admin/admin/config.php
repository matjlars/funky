<?php
namespace funky\controllers\admin\admin;

class config{
	public function __construct(){
		f()->access->enforce('dev');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}

	public function index(){
		// handle saving
		if(isset($_POST['saveconfig'])){
			unset($_POST['saveconfig']);
			foreach($_POST['config'] as $key=>$val){
				f()->config->$key = $val;
			}
			if(!empty($_POST['newkey']) && !empty($_POST['newval'])){
				$key = $_POST['newkey'];
				$val = $_POST['newval'];
				f()->config->$key = $val;
			}
		}

		$vars = f()->config->all();
		return f()->view->load('admin/admin/config/index', array(
			'vars'=>$vars,
		));
	}
}
