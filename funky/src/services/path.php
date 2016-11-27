<?php
namespace funky\services;

// This service is for getting information regarding server paths as well as URLs.
class path
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
			throw new \exception('unable to determine document root in path service.');
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
		// sanitize it a bit:
		$path = ltrim($path, '/');
		
		// send the redirect header:
		header('Location: '.$this->url.$path);
		die();
	}
	
	// returns the full path to the $path specified relative to the site's directory (i.e. '/home/yourusername/yoursite/'.$path)
	public function php($path='')
	{
		return $this->php.$path;
	}
	
	// returns the full server path to the document root
	public function docroot($path='')
	{
		return $this->docroot.$path;
	}
	
	// use this function to test whether we are currently on this page:
	public function iscurrent($path='')
	{
		if($path == ltrim($_SERVER['REQUEST_URI'],'/')) return true;
		return false;
	}
}