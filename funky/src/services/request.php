<?php
namespace funky\services;

class request
{
	public function perform()
	{
		try{
			// get this request's content
			$content = f()->router->route();
			
			// render the content in the template
			f()->response->content = f()->template->render($content);
			
			// send the response
			f()->response->send();
		}catch(\exception $e){
			f()->debug->exception($e);
			exit(1);
		}
	}
	public function isxhr()
	{
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}
		return false;
	}
	// returns which type of HTTP method the current request is using.
	// this will probably be either '', 'GET', or 'POST'
	public function method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	// returns TRUE if this request is using https, FALSE otherwise
	public function issecure()
	{
		if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])){
			// this request has a load balancer in front of it.
			if(strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') return true;
		}else{
			if(!empty($_SERVER['HTTPS'])) return true;
		}
		// could not find a way to tell it's secure, so it isn't:
		return false;
	}
}