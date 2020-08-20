<?php
namespace funky\services;

class url
{
	private $baseurl;
	
	public function __construct()
	{
		// figure out the base url 
		if(isset(f()->config->baseurl)){
			$this->baseurl = f()->config->baseurl;
		}else{
			// try to figure it out:
			$this->baseurl = 'http';
			if(f()->request->issecure()) $this->baseurl .= 's';
			$this->baseurl .= '://';
			$this->baseurl .= $_SERVER['SERVER_NAME'];
		}

		// add a slash to the end if there isn't one:
		if(substr($this->baseurl, -1, 1) != '/'){
			$this->baseurl .= '/';
		}
	}

	public function get($path='')
	{
		// strip start slashes because the baseurl already has one
		$path = ltrim($path, '/');
		
		// piece it together
		return $this->baseurl.$path;
	}

	public function current()
	{
		return $this->get($_SERVER['REQUEST_URI']);
	}

	public function iscurrent($path)
	{
		$path = trim($path, '/');
		$request_uri = trim($_SERVER['REQUEST_URI'], '/');
		if($path == $request_uri) return true;
		return false;
	}

	// returns the canonical URL if baseurl is in config
	// returns FALSE if there is no baseurl in the config
	public function canonical($path)
	{
		// only do this on an env with an explicit baseurl and path set
		if(!isset(f()->config->baseurl) || empty($path)) return '';

		// build the full url
		return f()->url->get($path);
	}
}
