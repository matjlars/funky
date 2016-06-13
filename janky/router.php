<?php


// if it's not a php request, return false so the built in php server loads the file normally
// this is for .css, .js, .jpg, .gif, or any other file type besides php files
if(substr($_SERVER['SCRIPT_NAME'], -4) != '.php'){
	return false;
}


// in this context, it's a php request, so load janky and continue:
// load the janky framework
require 'janky.php';