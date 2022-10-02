<?php
namespace funky\services;

// This service provides useful information about the site
class info{
	// returns an array of all controllers on this site
	public function controllers(){
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
	public function models(){
		return array_unique($this->files('src/models', 'php'));
	}

	// turns a path into an array of paths that can include stuff in the framework
	// override this with additional paths if you want to add more paths for some reason
	public function paths($path=''){
		return array(
			f()->path->php($path),
			f()->path->php('funky/'.$path),
		);
	}

	// returns an array of filenames that are in the given path, in the framework or in the site.
	// optionally only get files with the given extensions.
	// i.e. pass ['php'] to only get files that have the ".php" extension
	public function files($path, $extensions=array()){
		if(is_string($extensions)) $extensions = [$extensions];
		$files = array();
		foreach($this->paths($path) as $p){
			if(!file_exists($p)) continue;
			foreach(scandir($p) as $key=>$file){
				if($file != '.' && $file != '..'){
					// if we're not filtering by extension or it has the correct extension
					if(empty($extensions) || in_array(pathinfo($file, PATHINFO_EXTENSION), $extensions)){
						$files[] = pathinfo($file, PATHINFO_FILENAME);
					}
				}
			}
		}
		return $files;
	}
}