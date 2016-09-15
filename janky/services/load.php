<?php
// you can't override this service because of how it's loaded in the site constructor.. sorry.
// Let MisterMashu (mistermashu@gmail.com) know if you want him to figure out a way to do that.
// basically, this class is good for loading in models, views, controllers, and services in such a way where you can have global files and site-specific files and it handles it well.
class load extends j_service
{
	public function view($name,$data=array())
	{
		// Set up variables for the view:
		extract($data);
		
		// Check for a site-specific view:
		$siteviewpath = j()->path->php('views/'.$name.'.php');
		if(file_exists($siteviewpath))
		{
			include $siteviewpath;
			return true;
		}
		
		// Check for a global view (in DOC_ROOT/../janky/views/)
		$globalviewpath = j()->path->php('janky/views/'.$name.'.php');
		if(file_exists($globalviewpath))
		{
			include $globalviewpath;
			return true;
		}
		
		// Otherwise, err out:
		j()->debug->error('View '.$name.' not found in site-specific ('.$siteviewpath.') or global views ('.$globalviewpath.') directory.');
	}
	
	// Returns an object of type $name controller
	public function controller($name)
	{
		// figure out the controller name, independent of any path it came with:
		$controllername = $name;
		$lastslash = strrpos($name, '/');
		if($lastslash !== false)
		{
			$controllername = substr($name, $lastslash+1);
		}
		
		// figure out the global and site-specific paths:
		$globalpath = j()->path->php('janky/controllers/'.$name.'.php');
		$controllerpath = j()->path->php('controllers/'.$name.'.php');
		
		// this will contain the class name to instantiate at the end:
		$class = '';
		
		// first see if there's a global controller:
		if(is_file($globalpath))
		{
			require_once $globalpath;
			$class = $controllername;
			
			// if it didn't define this class, err out to let the dev know the file is wrong:
			if(!in_array($class, get_declared_classes()))
			{
				j()->debug->error('Your controller class in '.$globalpath.' must be named "'.$class.'"');
			}
		}
		
		// now see if there's a custom one for this particular web site:
		if(is_file($controllerpath))
		{
			require_once $controllerpath;
			$class = 'my_'.$controllername;
			
			// err out if there was supposed to be a class in that file but there wasn't:
			if(!in_array($class, get_declared_classes()))
			{
				j()->debug->error('You must create a controller class called "'.$class.'" in this file: '.$controllerpath.'.');
			}
		}
		
		if(empty($class))
		{
			j()->debug->error('Controller '.$name.' not found in global or site-specific context.');
		}
		else
		{
			return new $class();
		}
	}
	
	// requires the model file(s) and returns TRUE if successful or FALSE if unsuccessful
	public function model($name)
	{
		// Check for site-specific model file:
		$modelpath = j()->path->php('models/'.$name.'.php');
		if(file_exists($modelpath))
		{
			require_once $modelpath;
			return true;
		}
		
		return false;
	}
	
	// requires the service file and a potential extended one, instantiates one and returns it:
	public function service($name)
	{
		$servicepath = j()->path->php('janky/services/'.$name.'.php');
		$custompath = j()->path->php('services/'.$name.'.php');
		
		$class = '';
		
		// first see if there's a site-wide service:
		if(is_file($servicepath))
		{
			require_once $servicepath;
			$class = $name;
		}
		
		// now see if there's a custom one for this particular web site:
		if(is_file($custompath))
		{
			require_once $custompath;
			$class = 'my_'.$name;
		}
		
		if(empty($class))
		{
			j()->debug->error('Service '.$name.' not found in global or site-specific context.');
		}
		else
		{
			return new $class();
		}
	}
}