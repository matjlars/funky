<?php
namespace funky\services;

class env
{
	// returns true or false depending on if this is running on a local server
	// so basically, a TRUE means it's being developed, and a FALSE means it's on a hosted environment
	// keep in mind, this can be spoofed, so don't allow permissions due to this
	public function islocal()
	{
		if($_SERVER['SERVER_NAME'] == 'localhost') return true;
		return false;
	}
}