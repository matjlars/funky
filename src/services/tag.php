<?php
namespace funky\services;

// this class provides some shortcuts to some common tags integrated with other funky stuff
// all of these functions return a string so you can easily print it in a view
class tag
{
	public function canonical($path)
	{
		// only do this on an env with an explicit baseurl and path set
		if(!isset(f()->config->baseurl) || empty($path)) return '';

		// build the full url
		$url = f()->url->get($path);

		// render the tag
		return '<link rel="canonical" href="'.$url.'"/>';
	}

	public function javascript($path)
	{
		return '<script src="'.f()->url->get($path).'"></script>';
	}
}