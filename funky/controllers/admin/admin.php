<?php
class admin extends f_controller
{
	public function __construct()
	{
		// if we are not logged in as matt, err out.
		if(!f()->access->issuperuser())
		{
			f()->path->redirect('admin/index/login');
		}
		
		// set the template to the admin template:
		f()->template->view = 'admin';
		f()->template->sections = array(
			'admin/admin/subnav',
		);
	}
	
	public function index()
	{
		f()->load->view('admin/admin/index');
	}
	public function validator()
	{
		f()->load->view('admin/admin/validator');
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
		f()->load->view('admin/admin/admintools', array(
			'tools'=>$tools,
		));
	}
	public function users()
	{
		$users = array();
		f()->load->view('admin/admin/users', array(
			'users'=>$users,
		));
	}
	public function logs()
	{
		$logs = array();
		f()->load->view('admin/admin/logs', array(
			'logs'=>$logs,
		));
	}
	public function config()
	{
		$vars = array();
		f()->load->view('admin/admin/config', array(
			'vars'=>$vars,
		));
	}
	public function sitemap()
	{
		f()->load->view('admin/admin/sitemap');
	}	
}