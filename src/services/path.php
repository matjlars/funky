<?php
namespace funky\services;

// This service is for getting information regarding server paths as well as URLs.
class path{
	private $baseurl;

	// the absolute path to the folder in which hostable files are kept (index.php, css files, js files, images, etc.)
	private $docroot;

	// the absolute path in which non-public php files are kept (controllers folder, views folder, models folder, config.php, public_html folder, etc.)
	private $php;

	public function __construct(){
		// determine the document root:
		if(isset($_SERVER['DOCUMENT_ROOT'])){
			$this->docroot = $_SERVER['DOCUMENT_ROOT'].'/';
		}elseif(!empty($_SERVER['argv'])){
			$this->docroot = reset($_SERVER['argv']).'/'; // this works for command line calls (like for cron jobs)
		}else{
			throw new \Exception('unable to determine document root in path service.');
		}
		
		// generate the base path by just stripping off the last folder:
		$this->php = dirname($this->docroot).'/';
	}
	
	// returns the full path to the $path specified relative to the site's directory (i.e. '/home/yourusername/yoursite/'.$path)
	public function php($path=''){
		return $this->php.$path;
	}

	// returns the full path to the $path specified relative to the funky directory (in vendor/mistermashu/funky/)
	public function funky($path=''){
		return dirname(dirname(__DIR__)).'/'.$path;
	}
	
	// returns the full server path to the document root
	public function docroot($path=''){
		return $this->docroot.$path;
	}
}