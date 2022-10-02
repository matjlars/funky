<?php
namespace funky\services;

class url{
	protected $baseurl;
	protected $current_path;
	
	public function __construct(){
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

		// figure out the current path
		if(!empty($_SERVER['REQUEST_URI'])){
			$this->current_path = trim($_SERVER['REQUEST_URI'], '/');
		}else{
			$this->current_path = '';
		}
	}

	public function get($path=''){
		// strip start slashes because the baseurl already has one
		$path = ltrim($path, '/');
		
		// piece it together
		return $this->baseurl.$path;
	}

	public function current(){
		return $this->get($this->current_path);
	}

	// returns true if the given $path is the current user's path exactly
	public function iscurrent($path){
		$path = trim($path, '/');
		if($path == $this->current_path) return true;
		return false;
	}

	// returns true if the given $path is the beginning of the user's current path.
	public function starts_with($path){
		$path = trim($path, '/');
		return strpos($this->current_path, $path) === 0;
	}

	// returns the canonical URL if baseurl is in config
	// returns FALSE if there is no baseurl in the config
	public function canonical($path){
		// only do this on an env with an explicit baseurl and path set
		if(!isset(f()->config->baseurl) || empty($path)) return '';

		// build the full url
		return f()->url->get($path);
	}
}
