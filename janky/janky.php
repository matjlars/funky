<?php

// this file needs to be included before every request in order to have access to j()



// load a few required core files:
require 'core/j_model.php';
require 'core/j_controller.php';
require 'core/j_service.php';


// this is the whole janky framework (besides all the awesome services)
class j_janky
{
	public static $j;
	private $services= array();
	
	public function __construct()
	{
		session_start();
		
		// Register an autoload function used for models:
		/* CAN ONLY DO THIS ONCE WE'RE RUNNING PHP 5 or WHATEVER.. DELETE THE __autoload() FUNCTION BELOW IF THIS ENDS UP WORKING..
		spl_autoload_register(function($model){
			j()->load->model($model);
		});
		*/
		
		// determine j() path to load a few
		$servicespath = dirname(__FILE__).'/services';
		
		// Load the path service so our loader knows where to load stuff from
		require_once $servicespath.'/path.php';
		$this->services['path'] = new path();
		
		// Load the loader so we don't try to load the loader to load the loader later (only chuck norris can do that)
		require_once $servicespath.'/load.php';
		$this->services['load'] = new load();
	}
	
	// auto-load and access services:
	public function __get($key)
	{
		if(!isset($this->services[$key]))
		{
			$this->services[$key] = $this->load->service($key);
		}
		return $this->services[$key];
	}
}

// Here, we'll define a function for attempting to autoload models:
// This way, you can just use the model classes without needing to include/require/load them.
function __autoload($model)
{
	if(!j()->load->model($model))
	{
		j()->debug->error('Class '.$model.' not found.');
	}
}


// instantiate j_janky
j_janky::$j = new j_janky();


// define the global function that allows you to easily access the j_janky singleton object
function j()
{
	return j_janky::$j;
}