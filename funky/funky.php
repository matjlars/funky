<?php
// This file is included at the very beginning of all requests.
// That happens via the .htaccess file.


// turn on error reporting:
error_reporting(E_ALL);


// define custom error handling function:
function handleError($level, $message, $file, $line, $context)
{
	// cancel the template so it doesn't show half a page
	f()->template->cancel();
	
	// show a really nice error if the user is an adminadmin
	if(f()->access->isloggedin()){
		if(f()->access->hasrole('adminadmin')){
			// show an in-depth error
			f()->load->view('errors/devphp', array(
				'level'=>$level,
				'message'=>$message,
				'file'=>$file,
				'line'=>$line,
				'context'=>$context,
			));
			// and that's it.
			exit(1);
		}
	}
	
	// in this context, the user is not an adminadmin.
	// email a nice error to the dev if the config value is set
	if(isset(f()->config->devemail)){
		$body = '';
		$body .= 'error details:'."\n";
		$body .= 'level: '.$level."\n";
		$body .= 'message: '.$message."\n";
		$body .= 'file: '.$file."\n";
		$body .= 'line: '.$line."\n";
		$body .= 'context: '.$context."\n";
		$body .= "\n";
		if(!empty($_SESSION)){
			$body .= 'Session Data: '."\n";
			$body .= implode(', ', $_SESSION)."\n";
		}
		if(!empty($_COOKIES)){
			$body .= 'Cookie data: '."\n";
			$body .= implode(', ', $_COOKIES)."\n";
		}
		if(!empty($_GET)){
			$body .= 'Get parameters: '."\n";
			$body .= implode(', ', $_GET)."\n";
		}
		if(!empty($_POST)){
			$body .= 'Post parameters: '."\n";
			$body .= implode(', ', $_POST)."\n";
		}
		if(!empty($_SERVER)){
			$body .= 'Server data: '."\n";
			$body .= implode(', ', $_SERVER)."\n";
		}
		
		f()->email->to(f()->config->devemail);
		f()->email->subject('Website error encountered by user');
		f()->email->body($body);
		f()->email->send();
	}
	// show a generic error to the user:
	f()->load->view('errors/php');
	
	// don't do anything else
	exit(1);
}
set_error_handler('handleError');


// start the session no matter what:
session_start();


// this function automatially loads classes
// $classname is either like \funky\models\classname
// $classname is else like \models\classname
// if $classname is like \models\classname, it automatically creates it if it doesn't exist and a funky one does.
function __autoload($classname)
{
	// figure out a paths to remove the dependency on the path service, so we can load the path service:
	$projectroot = dirname($_SERVER['DOCUMENT_ROOT']).'/';
	
	// try to load class from either /funky/src/ or /src/
	$tokens = explode('\\', $classname);
	// add beginning of path to the path
	if(isset($tokens[0]) && $tokens[0] == 'funky'){
		// load from /funky
		unset($tokens[0]);
		$path = $projectroot.'funky/src/'.implode('/', $tokens).'.php';
	}else{
		// load from site's /src
		$fpath = $projectroot.'funky/src/'.implode('/', $tokens).'.php';
		$path = $projectroot.'src/'.implode('/', $tokens).'.php';
		
		// if the site-specific one doesn't exist, create it quick:
		if(!is_file($path)){
			if(is_file($fpath)){
				// a site-specific one doesn't exist, but a funky one does.
				// automatically create a new class that is site-specific:
				$funkyclass = '\\funky\\'.$classname;
				$lastslash = strrpos($classname, '\\');
				$namespace = '';
				$php = '';
				if($lastslash !== false){
					$namespace = substr($classname, 0, $lastslash);
					$classname = substr($classname, $lastslash+1);
					$php = 'namespace '.$namespace.";\n";
				}
				$php .= 'class '.$classname.' extends '.$funkyclass.'{}';
				eval($php);
				return;
			}else{
				throw new \exception('trying to load class '.$classname.' but neither '.$path.' nor '.$fpath.' exist.');
			}
		}
		// in this context, we know there is a file at $path
	}
	if(!is_file($path)){
		throw new \exception('trying to load class '.$classname.' but file '.$path.' does not exist');
	}
	include $path;
	if(!in_array($classname, get_declared_classes())){
		throw new \exception('File '.$path.' exists but does not contain class '.$classname);
	}
}


// this is a little class just to make the call to f() nicer.
// this way, you can just say f()->db for the "db" singleton service.
class funky_servicecontainer
{
	private $services = array();
	
	// auto-load and access services:
	public function __get($key)
	{
		if(!isset($this->services[$key]))
		{
			$serviceclass = '\\services\\'.$key;
			$this->services[$key] = new $serviceclass();
			if($this->services[$key] == null){
				throw new Exception('No such service '.$key);
			}
		}
		return $this->services[$key];
	}
}


// define the global function that allows you to easily access any of the service singletons
function f()
{
	static $container = null;
	if(is_null($container)) $container = new funky_servicecontainer();
	return $container;
}


// now perform the request using the perform() function in the "request" service:
f()->request->perform();