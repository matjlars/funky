<?php
namespace funky\services;

// this class provides some shortcuts to some common tags integrated with other funky stuff
// all of these functions return a string so you can easily print it in a view
class tag
{
	public function canonical()
	{
		// generate a full url for the current page
		$href = f()->url->get($_SERVER['REQUEST_URI']);
		// strip slashes off the end to improve consistency
		$href = rtrim($href, '/');
		return '<link rel="canonical" href="'.$href.'"/>';
	}
	public function javascript($path)
	{
		return '<script src="'.f()->url->get($path).'"></script>';
	}
}