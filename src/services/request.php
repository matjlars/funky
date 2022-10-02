<?php
namespace funky\services;

class request{
	public function perform(){
		try{
			// get page content
			$content = f()->router->route();
			
			// stick the page content in the template
			$content = f()->template->render($content);
			
			// send a 200 ok response with all the content
			f()->response->send(200, $content);
		}catch(\exception $e){
			$content = f()->debug->exception($e);
			f()->response->send(500, $content);
			exit(1);
		}
	}

	public function isxhr(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}
		return false;
	}

	// returns which type of HTTP method the current request is using.
	// this will probably be either '', 'GET', or 'POST'
	public function method(){
		return $_SERVER['REQUEST_METHOD'];
	}

	// returns the format for the requested "file"
	// so if the request path is "/sitemap.xml", this will return "xml"
	// defaults to "php" if there is nothing.
	public function format(){
		if(!empty($_SERVER['REQUEST_URI'])){
			return pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION);
		}
		return 'php';
	}

	// returns TRUE if this request is using https, FALSE otherwise
	public function issecure(){
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