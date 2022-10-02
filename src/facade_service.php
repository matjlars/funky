<?php
namespace funky;

// this class can be used to facilitate multiple options for a service
// a service can extend this class
// then the service options are in a sub-directory
// see the uploads service for an example
abstract class facade_service{
	// this will contain the reference to the actual service object.
	// this just needs to be constructed in your facade's constructor.
	protected $service;

	public function __call($name, $args){
		return call_user_func_array([$this->service, $name], $args);
	}
	public function __get($name){
		return $this->service->$name;
	}
	public function __set($name, $val){
		$this->service->$name = $val;
	}

	// loads a sub-service by name and returns it
	// throws an exception if it can't find it
	protected function load($name){
		$service = $this->service_name();
		$class = '\\services\\'.$service.'\\'.$name;

		// try a site-specific one first
		if(class_exists($class)){
			return new $class();
		}

		// prepend funky namespace to try that
		$class = '\\funky'.$class;
		if(class_exists($class)){
			return new $class();
		}

		throw new \Exception("Service $service has no subservice named $name");
	}

	// returns the public name of this service.
	// for example, "uploads"
	protected function service_name(){
		$name = get_called_class();
		$lastSlash = strrpos($name, '\\');
		return substr($name, $lastSlash+1);
	}
}
