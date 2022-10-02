<?php
namespace funky\services;

class admin{
	// returns an array for the admin nav links
	// keys are paths (i.e. "/admin/testimonials")
	// values are labels (i.e. "Testimonials")
	public function nav_links(){
		// no links if not logged in
		if(!f()->access->isloggedin()) return [];

		// no links if no "admin" role
		if(!f()->access->hasrole('admin')) return [];

		// get links from the site and funky
		$links = array_merge($this->get_site_links(), $this->get_funky_links());

		// add some links for dev users
		if(f()->access->hasrole('dev')){
			$links = array_merge($links, $this->get_dev_links());
		}

		return $links;
	}

	protected function get_site_links(){
		return $this->glob_links(f()->path->php('src/controllers/admin/*.php'));
	}
	
	protected function get_funky_links(){
		$paths = $this->glob_links(f()->path->funky('src/controllers/admin/*.php'));

		// exclude some ones we don't want to link to:
		unset($paths['/admin/index']);
		unset($paths['/admin/login']);
		unset($paths['/admin/logout']);

		return $paths;
	}

	protected function get_dev_links(){
		return [
			'/admin/admin/users'=>'Users',
			'/admin/admin/config'=>'Config',
			'/admin/admin/smtp'=>'SMTP',
			'/admin/admin/s3'=>'S3',
			'/admin/admin/database'=>'Database',
		];
	}

	protected function glob_links($pattern){
		$links = [];
		foreach(\glob($pattern) as $filename){
			$name = pathinfo($filename, PATHINFO_FILENAME);
			$links['/admin/'.$name] = ucwords(str_replace('_', ' ', $name));
		}
		return $links;
	}
}
