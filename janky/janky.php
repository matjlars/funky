<?php

// this file needs to be included before every request in order to have access to j()



// load a few required core files:
require 'core/j_model.php';
require 'core/j_controller.php';
require 'core/j_module.php';


// this is the whole janky framework (besides all the awesome modules)
class j_janky
{
	public static $j;
	private $modules = array();
	
	public function __construct()
	{
		session_start();
		
		// Register an autoload function used for models:
		/* CAN ONLY DO THIS ONCE WE'RE RUNNING PHP 5 or WHATEVER.. DELETE THE __autoload() FUNCTION BELOW IF THIS ENDS UP WORKING..
		spl_autoload_register(function($model){
			site()->load->model($model);
		});
		*/
		
		// determine site() path to load a few
		$modulespath = dirname(__FILE__).'/modules';
		
		// Load the path module so our loader knows where to load stuff from
		require_once $modulespath.'/path.php';
		$this->modules['path'] = new path();
		
		// Load the loader so we don't try to load the loader to load the loader later (only chuck norris can do that)
		require_once $modulespath.'/load.php';
		$this->modules['load'] = new load();
	}
	
	// auto-load and access site modules:
	public function __get($key)
	{
		if(!isset($this->modules[$key]))
		{
			$this->modules[$key] = $this->load->module($key);
		}
		return $this->modules[$key];
	}
}

// Here, we'll define a function for attempting to autoload models:
// This way, you can just use the model classes without needing to include/require/load them.
function __autoload($model)
{
	if(!j()->load->model($model))
	{
		site()->debug->error('Class '.$model.' not found.');
	}
}


// instantiate j_janky
j_janky::$j = new j_janky();


// define the global function that allows you to easily access the j_janky singleton object
function j()
{
	return j_janky::$j;
}


// do the request:
j()->request->start();
j()->router->route();
j()->request->stop();