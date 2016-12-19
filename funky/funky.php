<?php
// This file is included at the very beginning of all requests.
// That happens via the .htaccess file.


// turn on error reporting:
error_reporting(E_ALL);


// pass all php errors along to the debug->error() function
set_error_handler(function($level, $message, $file, $line, $context){
	f()->debug->error($level, $message, $file, $line, $context);
});


// catch E_FATAL errors, too:
register_shutdown_function(function(){
	// try to get the last error
	$error = error_get_last();
	
	// if there wasn't an error, just exit
	if(is_null($error)) exit(1);
	
	// display the error
	f()->debug->error($error['type'], $error['message'], $error['file'], $error['line'], array());
});


// start the session no matter what:
session_start();


// this function automatially loads classes
// $classname is either like \funky\models\classname
// $classname is else like \models\classname
// if $classname is like \models\classname, it automatically creates it if it doesn't exist and a funky one does.
function __autoload($classname)
{
	// figure out a paths to remove the dependency on the path service, so we can load the path service:
	$projectroot = dirname($_SERVER['DOCUMENT_ROOT']).'/';
	
	// try to load class from either /funky/src/ or /src/
	$tokens = explode('\\', $classname);
	// add beginning of path to the path
	if(isset($tokens[0]) && $tokens[0] == 'funky'){
		// load from /funky
		unset($tokens[0]);
		$path = $projectroot.'funky/src/'.implode('/', $tokens).'.php';
	}else{
		// load from site's /src
		$fpath = $projectroot.'funky/src/'.implode('/', $tokens).'.php';
		$path = $projectroot.'src/'.implode('/', $tokens).'.php';
		
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


// this is a little class just to make the call to f() nicer.
// this way, you can just say f()->db for the "db" singleton service.
class funky_servicecontainer
{
	private $services = array();
	
	// auto-load and access services:
	public function __get($key)
	{
		if(!isset($this->services[$key]))
		{
			$serviceclass = '\\services\\'.$key;
			$this->services[$key] = new $serviceclass();
			if($this->services[$key] == null){
				throw new Exception('No such service '.$key);
			}
		}
		return $this->services[$key];
	}
}


// define the global function that allows you to easily access any of the service singletons
function f()
{
	static $container = null;
	if(is_null($container)) $container = new funky_servicecontainer();
	return $container;
}


// now perform the request using the perform() function in the "request" service:
f()->request->perform();