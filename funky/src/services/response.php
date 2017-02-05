<?php
namespace funky\services;

class response
{
	private $headers = array();
	public $content = '';
	
	// call this function to set a header value
	// subsequent calls with the same key will overwrite the previous value
	public function addHeader($key, $val){
		$this->headers[$key] = $val;
	}
	// removes a header that was previously set with addHeader()
	public function deleteHeader($key){
		unset($this->headers[$key]);
	}
	// sends the request and exits php
	// this function uses the headers set with addHeader()
	// it also uses the public $content property as the content body
	public function send($code=200){
		// set the response code. this will automatically configure the HTTP header
		http_response_code($code);
		
		// send the custom headers
		foreach($this->headers as $key=>$val){
			header($key.': '.$val);
		}
		
		// output the body content
		echo $this->content;
		
		// exit successfully
		exit(0);
	}
	// sends a redirect response
	public function redirect($newurl, $code=302){
		header('Location: '.$newurl, true, $code);
		exit(0);
	}
	// outputs a file as the response
	public function sendFile($path){
		$mimetype = mime_content_type($path);
		$filename = basename($path);
		header('Content-Type: '.$mimetype);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		readfile($path);
		exit(0);
	}
	public function send404(){
		http_response_code(404);
		echo f()->view->load('errors/404');
		exit(0);
	}
	// call this function anywhere in your request to set headers to disable caching
	public function disableCache(){
		$this->addHeader('Cache-Control', 'no-cache, must-revalidate');
		$this->addHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
	}
}