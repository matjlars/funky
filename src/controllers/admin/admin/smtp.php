<?php
namespace funky\controllers\admin\admin;

class smtp{
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
			f()->flash->success('Saved SMTP config!');
		}

		return f()->view->load('admin/admin/smtp/index');
	}
}