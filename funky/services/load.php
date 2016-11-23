<?php
// you can't override this service because of how it's loaded in the site constructor.. sorry.
// Let MisterMashu (mistermashu@gmail.com) know if you want him to figure out a way to do that.
// basically, this class is good for loading in models, views, controllers, and services in such a way where you can have global files and site-specific files and it handles it well.
class load
{
	public function view($name,$data=array())
	{
		// Set up variables for the view:
		extract($data);
		
		// Check for a site-specific view:
		$siteviewpath = f()->path->php('views/'.$name.'.php');
		if(file_exists($siteviewpath))
		{
			include $siteviewpath;
			return true;
		}
		
		// Check for a global view (in DOC_ROOT/../funky/views/)
		$globalviewpath = f()->path->php('funky/views/'.$name.'.php');
		if(file_exists($globalviewpath))
		{
			include $globalviewpath;
			return true;
		}
		
		// Otherwise, err out:
		throw new exception('View '.$name.' not found in site-specific ('.$siteviewpath.') or global views ('.$globalviewpath.') directory.');
	}
	
	// requires the service file and a potential extended one, instantiates one and returns it:
	public function service($name)
	{
		$servicepath = f()->path->php('funky/services/'.$name.'.php');
		$custompath = f()->path->php('services/'.$name.'.php');
		
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
			throw new exception('Service '.$name.' not found in global or site-specific context.');
		}
		else
		{
			return new $class();
		}
	}

	public function field($name, $typename, $args=array())
	{
		$class = '\\funky\\fields\\'.$typename;
		$field = new $class($name, $args);
		return $field;
	}
	public function fields($arr)
	{
		$f = array();
		foreach($arr as $a){
			$name = $a[0];
			$type = $a[1];
			$args = array();
			if(isset($a[2])) $args = $a[2];
			$f[$name] = f()->load->field($name, $type, $args);
		}
		return $f;
	}
}