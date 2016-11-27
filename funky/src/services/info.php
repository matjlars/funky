<?php
namespace funky\services;

// This service provides useful information about the site
class info
{
	// returns an array of all controllers on this site
	public function controllers()
	{
		$files = scandir(f()->path->php('controllers'));
		
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
	// returns an array of all model class names (without the namespace)
	public function models()
	{
		return array_unique($this->files('src/models'));
	}
	// turns a path into an array of paths that can include stuff in the framework
	// override this with additional paths if you want to add more paths for some reason
	public function paths($path='')
	{
		return array(
			f()->path->php($path),
			f()->path->php('funky/'.$path),
		);
	}
	// returns an array of filenames that are in the given path, in the framework or in the site.
	public function files($path)
	{
		$files = array();
		foreach($this->paths($path) as $p){
			if(!file_exists($p)) continue;
			foreach(scandir($p) as $key=>$file){
				if($file != '.' && $file != '..'){
					$files[] = pathinfo($file, PATHINFO_FILENAME);
				}
			}
		}
		return $files;
	}
}