<?php
namespace funky\services;

class view{
	// loads a view and returns it as a string
	public function load($path, $data=[]){
		// Set up variables for the view:
		extract($data);
		
		// Check for a site-specific view:
		$siteviewpath = f()->path->php('views/'.$path.'.php');
		if(file_exists($siteviewpath)){
			ob_start();
			include $siteviewpath;
			return ob_get_clean();
		}
		
		// Check for a global view (in DOC_ROOT/../funky/views/)
		$globalviewpath = f()->path->funky('views/'.$path.'.php');
		if(file_exists($globalviewpath)){
			ob_start();
			include $globalviewpath;
			return ob_get_clean();
		}
		
		// Otherwise, err out:
		throw new \exception('View '.$path.' not found in site-specific ('.$siteviewpath.') or global views ('.$globalviewpath.') directory.');
	}

	// takes the same $path as load()
	// but just returns true if it exists
	// and false if it doesn't.
	public function exists($path){
		// Check for a site-specific view:
		$siteviewpath = f()->path->php('views/'.$path.'.php');
		if(file_exists($siteviewpath)){
			return true;
		}
		
		// Check for a global view (in DOC_ROOT/../funky/views/)
		$globalviewpath = f()->path->funky('views/'.$path.'.php');
		if(file_exists($globalviewpath)){
			return true;
		}

		return false;
	}
}
