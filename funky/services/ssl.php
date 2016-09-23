<?php
class ssl extends j_service
{
	// returns TRUE if SSL (HTTPS) is currently being used
	// returns FALSE if it isn't.
	public function isSecure()
	{
		$isSecure = false;
		if(!empty($_SERVER['HTTPS']))
		{
			$isSecure = true;
		}
		return $isSecure;
	}
	public function force()
	{
		if(!$this->isSecure())
		{
			// redirect to the same page, but with https
			header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			exit;
		}
	}
}