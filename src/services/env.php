<?php
namespace funky\services;

class env
{
	// returns true or false depending on if this is running on a local server
	// feel free to overwrite this function with your own conditions
	public function islocal()
	{
		// if the server name is "localhost", we're certainly on local
		if($_SERVER['SERVER_NAME'] == 'localhost') return true;
		
		// if the server name ends in ".local", we're also certainly on local
		if(substr($_SERVER['SERVER_NAME'], -6) == '.local') return true;
		
		// otherwise, we may not be, so default to no.
		return false;
	}
}