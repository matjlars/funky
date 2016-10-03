<?php

// turn on error reporting:
error_reporting(E_ALL);


// load a few required core files:
require 'core/f_model.php';


// this is the whole funky framework (besides all the awesome services)
class f_funky
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

// Here, we'll define a function for attempting to autoload models:
// This way, you can just use the model classes without needing to include/require/load them.
function __autoload($model)
{
	if(!f()->load->model($model))
	{
		f()->debug->error('Class '.$model.' not found.');
	}
}


// define the global function that allows you to easily access the f_funky singleton object
function f()
{
	static $f = null;
	if($f == null) $f = new f_funky();
	return $f;
}


// now perform the request:
f()->request->perform();