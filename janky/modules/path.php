<?php

// This module is for getting information regarding server paths as well as URLs.
class path extends j_module
{
	private $url; // the full url for this site, (i.e. http://example.com/)
	private $docroot; // the absolute path to the folder in which hostable files are kept (index.php, css files, js files, images, etc.)
	private $php; // the absolute path in which non-public php files are kept (controllers folder, views folder, models folder, config.php, public_html folder, etc.)
	
	public function __construct()
	{
		$this->url = 'http'.((empty($_SERVER['HTTPS']))?'':'s').'://'.$_SERVER['HTTP_HOST'].'/';
		
		// determine the document root:
		if(isset($_SERVER['DOCUMENT_ROOT']))
		{
			$this->docroot = $_SERVER['DOCUMENT_ROOT'].'/';
		}
		elseif(!empty($_SERVER['argv']))
		{
			$this->docroot = reset($_SERVER['argv']).'/'; // this works for command line calls (like for cron jobs)
		}
		else
		{
			j()->debug->error('unable to determine document root in path module.');
			exit(1);
		}
		
		// generate the base path by just stripping off the last folder:
		$this->php = dirname($this->docroot).'/';
	}
	
	// returns the full canonical url to the current page
	public function current_url()
	{
		return $this->url.ltrim($_SERVER['REQUEST_URI'],'/');
	}

	// returns the full canonical url to the given path (relative to i.e. 'http://www.mistermashu.com/')
	public function url($path='')
	{
		return $this->url.$path;
	}
	
	// redirects to the given $path
	public function redirect($path='')
	{
		header('Location: '.$this->url.$path);
		die();
	}
	
	/* this assumes certain server setups, so its not the best
	// returns the path to the base directory for this server account that has php files (i.e. '/home/yourusername/'.$path)
	public function basephp($path='')
	{
		return '/home/mrmashu/'.$path;
	}
	*/
	
	// returns the full path to the $path specified relative to the site's directory (i.e. '/home/yourusername/yoursite/'.$path)
	public function php($path='')
	{
		return $this->php.$path;
	}
	
	/* again, this assumes too much. feel free to make a function like this in your path module
	// returns the canonical URL to the Green Gear Designs resources folder, appending the given $path
	public function cdn($path='')
	{
		return 'http://cdn.example.com/'.$path;
	}
	*/
	
	// use this function to test whether we are currently on this page:
	public function iscurrent($path='')
	{
		if($path == ltrim($_SERVER['REQUEST_URI'],'/')) return true;
		return false;
	}
}