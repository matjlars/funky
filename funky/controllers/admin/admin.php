<?php
class admin extends j_controller
{
	public function __construct()
	{
		// if we are not logged in as matt, err out.
		if(!j()->access->issuperuser())
		{
			j()->path->redirect('admin/index/login');
		}
		
		// set the template to the admin template:
		j()->template->view = 'admin';
		j()->template->sections = array(
			'admin/admin/subnav',
		);
	}
	
	public function index()
	{
		j()->load->view('admin/admin/index');
	}
	public function validator()
	{
		j()->load->view('admin/admin/validator');
	}
	public function admintools()
	{
		$tools = array();
		foreach(array(
			'blog',
		) as $tool)
		{
			$tools[$tool] = array(
				'name'=>ucwords($tool),
				'status'=>'not installed',
			);
		}
		j()->load->view('admin/admin/admintools', array(
			'tools'=>$tools,
		));
	}
	public function users()
	{
		$users = array();
		j()->load->view('admin/admin/users', array(
			'users'=>$users,
		));
	}
	public function logs()
	{
		$logs = array();
		j()->load->view('admin/admin/logs', array(
			'logs'=>$logs,
		));
	}
	public function config()
	{
		$vars = array();
		j()->load->view('admin/admin/config', array(
			'vars'=>$vars,
		));
	}
	public function sitemap()
	{
		j()->load->view('admin/admin/sitemap');
	}	
}