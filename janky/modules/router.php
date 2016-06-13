<?php

/*
this class is responsible for routing URL requests to controllers.
for example, if you want to use the *photos* controller on a new site, simply make this file at DOC_ROOT/../controllers/photos.php:

class photos extends j_photos{}

this creates the *photos* controller for your site, thereby granting access to all the public functions in j_photos as route endpoints

*/
class router extends j_module
{
	public function route()
	{
		// test if it's a page:
		if($this->routepage()) return;
		if($this->routecontroller()) exit; // exit to avoid printing the page it was redirected to
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
		$uriparts = explode('/',$_SERVER['REQUEST_URI']);
		
		// make sure we have a controller
		if(empty($uriparts[1])) $this->route404();
		$controllername = $uriparts[1];
		
		// get method name:
		if(empty($uriparts[2]))
		{
			$methodname = 'index'; // default method name
		}
		else
		{
			$methodname = $uriparts[2];
		}
		
		// get all parameters:
		$params = array();
		for($i = 3; true; $i++)
		{
			if(!isset($uriparts[$i])) break;
			$params[$i-3] = $uriparts[$i];
		}
		
		// make sure this site has this controller
		$controllerfile = j()->path->php('controllers/'.$controllername.'.php');
		if(is_file($controllerfile))
		{
			// see if it has the method:
			$controller = site()->load->controller($controllername);
			if(method_exists($controller, $methodname))
			{
				call_user_func_array(array($controller,$methodname),$params);
				return true;
			}
			else
			{
				$this->route404(); // method doesn't exist. 404.
			}
		}
		
		return false;
	}
	
	// this functions routes to the 404 page.
	public function route404()
	{
		header('HTTP/1.0 404 Not Found', true, 404);
		j()->load->view('errors/404');
		exit;
	}
}