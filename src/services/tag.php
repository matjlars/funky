<?php
namespace funky\services;

// this class provides some shortcuts to some common tags integrated with other funky stuff
// all of these functions return a string so you can easily print it in a view
class tag
{
	public function canonical($path)
	{
		$url = f()->url->canonical($path);

		// no tag if no canonical url
		if(empty($url)) return '';

		return '<link rel="canonical" href="'.f()->url->canonical($path).'"/>';
	}

	public function javascript($path)
	{
		return '<script src="'.f()->url->get($path).'"></script>';
	}
}