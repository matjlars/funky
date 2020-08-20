<?php
namespace funky\services;

class admin
{
	// returns an array for the admin nav links
	// keys are paths (i.e. "/admin/testimonials")
	// values are labels (i.e. "Testimonials")
	public function nav_links()
	{
		// no links if not logged in
		if(!f()->access->isloggedin()) return [];

		// no links if no "admin" role
		if(!f()->access->hasrole('admin')) return [];

		// get links from the site and funky
		return array_merge($this->get_site_links(), $this->get_funky_links());
	}

	protected function get_site_links()
	{
		return $this->glob_links(f()->path->php('src/controllers/admin/*.php'));
	}
	
	protected function get_funky_links()
	{
		return $this->glob_links(f()->path->funky('vendor/mistermashu/funky/*.php'));
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
