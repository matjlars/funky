<?php

// This service provides useful information about the site
class info extends j_service
{
	// returns an array of all controllers on this site
	public function controllers()
	{
		$files = scandir(j()->path->php('controllers'));
		
		$controllers = array();
		foreach($files as $key=>$file)
		{
			if($file != '.' && $file != '..')
			{
				$controllers[] = pathinfo($file, PATHINFO_FILENAME);
			}
		}
		
		return $controllers;
	}
}