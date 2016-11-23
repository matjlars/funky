<?php

// turn on error reporting:
error_reporting(E_ALL);


// start the session no matter what:
session_start();



// this is the whole funky framework (besides all the awesome services)
class funky
{
	private $services= array();
	
	public function __construct()
	{
		// determine f() path to load a few
		$servicespath = dirname(__FILE__).'/services/';
		
		// Load the path service so our loader knows where to load stuff from
		require_once $servicespath.'path.php';
		$this->services['path'] = new path();
		
		// Load the loader so we don't try to load the loader to load the loader later (only chuck norris can do that)
		require_once $servicespath.'load.php';
		$this->services['load'] = new load();
	}
	
	// auto-load and access services:
	public function __get($key)
	{
		if(!isset($this->services[$key]))
		{
			$this->services[$key] = $this->load->service($key);
			if($this->services[$key] == null){
				throw new Exception('No such service '.$key);
			}
		}
		return $this->services[$key];
	}
}

// this function automatially loads classes
// $classname is either like \funky\models\classname
// $classname is else like \models\classname
// if $classname is like \models\classname, it automatically creates it if it doesn't exist and a funky one does.
function __autoload($classname)
{
	// try to load class from either /funky/src/ or /src/
	$tokens = explode('\\', $classname);
	// add beginning of path to the path
	if(isset($tokens[0]) && $tokens[0] == 'funky'){
		// load from /funky
		unset($tokens[0]);
		$path = f()->path->php('funky/src/').implode('/', $tokens).'.php';
	}else{
		// load from site's /src
		$fpath = f()->path->php('funky/src/').implode('/', $tokens).'.php';
		$path = f()->path->php('src/').implode('/', $tokens).'.php';
		
		// if the site-specific one doesn't exist, create it quick:
		if(!is_file($path)){
			if(is_file($fpath)){
				// a site-specific one doesn't exist, but a funky one does.
				// automatically create a new class that is site-specific:
				$funkyclass = '\\funky\\'.$classname;
				$lastslash = strrpos($classname, '\\');
				$namespace = '';
				$php = '';
				if($lastslash !== false){
					$namespace = substr($classname, 0, $lastslash);
					$classname = substr($classname, $lastslash+1);
					$php = 'namespace '.$namespace.";\n";
				}
				$php .= 'class '.$classname.' extends '.$funkyclass.'{}';
				eval($php);
				return;
			}else{
				throw new \exception('trying to load class '.$classname.' but neither '.$path.' nor '.$fpath.' exist.');
			}
		}
		// in this context, we know there is a file at $path
	}
	if(!is_file($path)){
		throw new \exception('trying to load class '.$classname.' but file '.$path.' does not exist');
	}
	include $path;
	if(!in_array($classname, get_declared_classes())){
		throw new \exception('File '.$path.' exists but does not contain class '.$classname);
	}
}


// define the global function that allows you to easily access the funky singleton object
function f()
{
	static $f = null;
	if($f == null) $f = new funky();
	return $f;
}


// now perform the request:
f()->request->perform();