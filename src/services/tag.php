<?php
namespace funky\services;

// this class provides some shortcuts to some common tags integrated with other funky stuff
// all of these functions return a string so you can easily print it in a view
class tag
{
	public function canonical($path='')
	{
		// only do this on an env with a canonicalhost config set
		if(!isset(f()->config->canonicalhost)) return '';

		// do not rely on this auto-detection.
		// you must supply the desired path in order to assure accuracy.
		if(empty($path)){
			$path = $_SERVER['REQUEST_URI'];
			if(substr($path, 0, 1) != '/') $path = '/'.$path;
			if(substr($path, -1, 1) != '/') $path = $path.'/';
		}

		$href = f()->config->canonicalhost.$path;
		return '<link rel="canonical" href="'.$href.'"/>';
	}

	public function javascript($path)
	{
		return '<script src="'.f()->url->get($path).'"></script>';
	}
}