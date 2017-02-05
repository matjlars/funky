<?php
namespace funky\services;

class view
{
	// loads a view and returns it as a string
	public function load($name,$data=array())
	{
		// Set up variables for the view:
		extract($data);
		
		// Check for a site-specific view:
		$siteviewpath = f()->path->php('views/'.$name.'.php');
		if(file_exists($siteviewpath))
		{
			ob_start();
			include $siteviewpath;
			return ob_get_clean();
		}
		
		// Check for a global view (in DOC_ROOT/../funky/views/)
		$globalviewpath = f()->path->php('funky/views/'.$name.'.php');
		if(file_exists($globalviewpath))
		{
			ob_start();
			include $globalviewpath;
			return ob_get_clean();
		}
		
		// Otherwise, err out:
		throw new \exception('View '.$name.' not found in site-specific ('.$siteviewpath.') or global views ('.$globalviewpath.') directory.');
	}
}
