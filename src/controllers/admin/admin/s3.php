<?php
namespace funky\controllers\admin\admin;

class s3{
	public function __construct(){
		f()->access->enforce('dev');
		f()->template->view = 'admin';
		f()->template->premainview = 'admin/admin/subnav';
	}

	public function index(){
		if(!empty($_POST)){
			foreach($_POST as $key=>$val){
				f()->config->$key = $val;
			}
			f()->flash->success('Saved S3 config');
		}

		return f()->view->load('admin/admin/s3/index');
	}
}
