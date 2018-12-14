<?php
namespace funky\services;

/*
this class is responsible for routing URL requests to controllers.
for example, if you want to use the *photos* controller on a new site, simply make this file at DOC_ROOT/../controllers/photos.php:

class photos extends f_photos{}

this creates the *photos* controller for your site, thereby granting access to all the public functions in f_photos as route endpoints

*/
class router
{
	public function route()
	{
		// try to get content from something.
		foreach(['page','controller'] as $func){
			$content = $this->$func();
			if(!empty($content)){
				return $content;
			}
		}
		// nothing had content, so 404:
		f()->response->send404();
	}
	
	// this function tests for pages that exist at files (like index.php for the homepage or any other page)
	public function page()
	{
		$path = $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'];

		// strip off the trailing slash:
		$path = rtrim($path, '/');
		
		// try a few different paths
		foreach([
			$path,
			$path.'.php',
			$path.'/index.php',
		] as $p){
			if(is_file($p)){
				ob_start();
				include $p;
				return ob_get_clean();
			}
		}
		
		// couldn't find this file, so let route() know we failed.
		return false;
	}
	
	// this function tests for if this site has a controller, and if it does, it makes sure a method is also specified.
	// if both a controller and method are given, it calls those.
	// keep in mind, you must override the controller per site to use funky controllers (see note at the top of this file)
	public function controller()
	{
		// if it's a controller we have on this site, load that, with the method and parameters.
		$uriparts = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));

		// get rid of blank parts
		$uriparts = array_filter($uriparts);

		// ignore the extension on all uri parts:
		$uripart_count = count($uriparts);
		for($i = 0; $i < $uripart_count; $i++){
			$uripart = $uriparts[$i];
			$dot = strpos($uripart, '.');
			if($dot !== false){
				$uriparts[$i] = substr($uripart, 0, $dot);
			}
		}
		
		// first, keep going with subdirectories until there isn't a subdirectory that matches:
		$i = 0; // this is which uripart we are currently concerned with
		$controllerfilepath = f()->path->php('src/controllers/');
		$globalfilepath = f()->path->funky('src/controllers/');
		$controllername = '';
		while(isset($uriparts[$i]) && (is_dir($controllerfilepath.$controllername.$uriparts[$i]) || is_dir($globalfilepath.$controllername.$uriparts[$i])))
		{
			$controllername .= $uriparts[$i].'/';
			$i++;
		}
		
		// get the controller name
		if(empty($uriparts[$i]))
		{
			$controllername .= 'index';
		}
		else // we have a name for a controller!
		{
			$controllername .= $uriparts[$i];
		}
		
		// include the controller file
		$controllerclass = '\\controllers\\'.str_replace('/', '\\', $controllername);
		
		// see if funky has a controller for this if the project doesn't:
		if(!class_exists($controllerclass)){
			$controllerclass = '\\funky'.$controllerclass;

			// if funky doesn't have a controller for this route, then it's probably a 404:
			if(!class_exists($controllerclass)){
				return false;
			}
		}

		// in this context, this class exists. instantiate it:
		$controller = new $controllerclass();
		$i++; // moving on to the method..
		
		// get method name:
		if(empty($uriparts[$i]))
		{
			$methodname = 'index'; // default method name
		}
		else
		{
			$methodname = $uriparts[$i];
		}
		
		// validate the method name
		if(method_exists($controller, $methodname))
		{
			$r = new \ReflectionMethod($controller, $methodname);
			if(!$r->isPublic()) return false;
		}
		else
		{
			// in this context, there is no explicit function for this methodname.
			// therefore, if there is no __call function, then this is not a valid controller request
			if(!method_exists($controller, '__call'))
			{
				// there is no function to call in this controller
				return false;
			}
		}
		$i++; // moving on to parameters
		
		// get all parameters:
		$params = array();
		$parami = 0;
		while(isset($uriparts[$i]))
		{
			$params[$parami] = $uriparts[$i];
			$i++; // move to next param in uri
			$parami++; // move to next param in $params array
		}
		
		// at this point, we have full knowledge of the function to call
		return $controller->$methodname(...$params);
	}
}