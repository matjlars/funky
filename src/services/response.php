<?php
namespace funky\services;

class response
{
	protected $headers = array();
	
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
	public function send($code=200, $content){
		$this->sendHeaders($code);
		echo $content;
		exit(0);
	}

	// responds with a simple "200 OK"
	// given an optional message, will also output that as a plain text string
	public function ok($message=''){
		http_response_code(200);
		if(!empty($message)) echo $message;
		exit(0);
	}

	// sends a "400 Bad Request" response with the given message
	// the message is required because it'd be hard to figure out what's wrong without one
	public function error($message){
		http_response_code(400);
		echo $message;
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
		$content = f()->view->load('errors/404');
		echo f()->template->render($content);
		exit(0);
	}

	// sends a JSON response with the given data array
	public function json($data=[]){
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		exit(0);
	}

	// call this function anywhere in your request to set headers to disable caching
	public function disableCache(){
		$this->addHeader('Cache-Control', 'no-cache, must-revalidate');
		$this->addHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
	}

	protected function sendHeaders($code){
		http_response_code($code);
		foreach($this->headers as $key=>$val){
			header($key.': '.$val);
		}
	}
}