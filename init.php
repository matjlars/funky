<?php
// This file is included at the very beginning of all requests.
// That happens via the .htaccess file.


// turn on error reporting:
error_reporting(E_ALL);

// pass all php errors along to the debug->error() function
set_error_handler(function($level, $message, $file, $line, $context){
	f()->debug->error($level, $message, $file, $line, $context);
});



// use composer autoloader:
$loader = require(dirname(dirname(__DIR__)).'/autoload.php');

// also load stuff from your project's src/ dir
// as a couple examples, a file at your src/test.php containing an un-namespaced class called "test"
// or a file at your src/models/test.php with a class "models\test"
$loader->addPsr4('', dirname($_SERVER['DOCUMENT_ROOT']).'/src');



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


// this is a little class just to make the call to f() nicer.
// this way, you can just say f()->db for the "db" singleton service.
class funky_servicecontainer
{
	private $services = array();
	
	// auto-load and access services:
	public function __get($key)
	{
		// instantiate this service if we haven't already:
		if(!isset($this->services[$key]))
		{
			$serviceclass = '\\services\\'.$key;

			// see if this project has a class for this service:
			if(class_exists($serviceclass)){
				$this->services[$key] = new $serviceclass();
				return $this->services[$key];
			}

			// in this context, this project has no class for this service.
			// now let's see if funky has a class for this service:
			$serviceclass = '\\funky'.$serviceclass;
			if(class_exists($serviceclass)){
				$this->services[$key] = new $serviceclass();
				return $this->services[$key];
			}

			// in this context, there is no class at all for this service.
			throw new Exception('No such service '.$key.'. Try creating a file at src/services/'.$key.'.php with a class called \\services\\'.$key);
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