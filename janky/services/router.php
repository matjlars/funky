<?php

/*
this class is responsible for routing URL requests to controllers.
for example, if you want to use the *photos* controller on a new site, simply make this file at DOC_ROOT/../controllers/photos.php:

class photos extends j_photos{}

this creates the *photos* controller for your site, thereby granting access to all the public functions in j_photos as route endpoints

*/
class router extends j_service
{
	public function route()
	{
		// test if it's a page:
		if($this->routepage()) return;
		if($this->routecontroller()) return;
		$this->route404();
	}
	
	// this function tests for pages that exist at files (like index.php for the homepage or any other page)
	public function routepage()
	{
		$path = $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'];
		
		// append 'index.php' on the end if it ends in /
		if(substr($path, -1) == '/'){
			$path .= 'index.php';
		}
		
		// see if it's correct already (already has .php)
		if(is_file($path)){
			include $path;
			return true;
		}
		
		// see if it just needs the .php
		$path2 = $path.'.php';
		if(is_file($path2)){
			include $path2;
			return true;
		}
		
		// see if it needs a /index.php
		$path2 = $path.'/index.php';
		if(is_file($path2)){
			include $path2;
			return true;
		}

		// couldn't find this file, so let route() know we failed.
		return false;
	}
	
	// this function tests for if this site has a controller, and if it does, it makes sure a method is also specified.
	// if both a controller and method are given, it calls those.
	// keep in mind, you must override the controller per site to use janky controllers (see note at the top of this file)
	public function routecontroller()
	{
		// if it's a controller we have on this site, load that, with the method and parameters.
		$uriparts = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
		
		// first, keep going with subdirectories until there isn't a subdirectory that matches:
		$i = 0; // this is which uripart we are currently concerned with
		$controllerfilepath = j()->path->php('controllers/');
		$controllername = '';
		while(isset($uriparts[$i]) && is_dir($controllerfilepath.$controllername.$uriparts[$i]))
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
		$controllerfilepath .= $controllername.'.php';
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
		$i++; // moving on to parameters:
		
		// get all parameters:
		$params = array();
		$parami = 0;
		while(isset($uriparts[$i]))
		{
			$params[$parami] = $uriparts[$i];
			$i++; // move to next param in uri
			$parami++; // move to next param in $params array
		}
		
		// at this point, we've expended all of our uri parts.
		if(file_exists($controllerfilepath))
		{
			// load the controller:
			$controller = j()->load->controller($controllername);
			
			// make sure it has the method:
			if(method_exists($controller, $methodname))
			{
				call_user_func_array(array($controller,$methodname),$params);
				return true;
			}
			else // the method doesn't exist in this controller:
			{
				j()->debug->error('in j()->router->routecontroller(): no method "'.$method.'" in the controller '.$controllername);
				return false;
			}
		}
		
		return false; // no controller
	}
	
	// this functions routes to the 404 page.
	public function route404()
	{
		header('HTTP/1.0 404 Not Found', true, 404);
		j()->load->view('errors/404');
		exit;
	}
}