<?php
namespace funky\services;

class debug
{
	public function dump($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}
	// returns a string containing a nice HTML error
	public function exception($e)
	{
		return f()->view->load('errors/exception', array(
			'e'=>$e,
		));
	}
	// this function displays a php error and exits with status 1
	// this function is called from the main funky/funky.php custom error handler function
	// you probably shouldn't call this function, but instead use PHP's built in "trigger_error" function
	public function error($level, $message, $file, $line, $context)
	{
		// show a really nice error if the user is running from a local server
		if(f()->env->islocal()){
			// show an in-depth error
			$content = f()->view->load('errors/devphp', array(
				'level'=>$level,
				'message'=>$message,
				'file'=>$file,
				'line'=>$line,
				'context'=>$context,
			));
			f()->response->send(500, $content);
			return;
		}
		
		// in this context, the user is not a dev.
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

		// log the error:
		error_log($message);

		// show a generic error to the user:
		$content = f()->view->load('errors/php');
		f()->response->send(500, $content);
	}
	// accepts a value of bit flags representing predefined PHP constants
	// returns a readable string (such as "E_WARNING") that is what error level it is
	public function errstr($level)
	{
		$constants = array_flip(array_slice(get_defined_constants(true)['Core'], 0, 15, true));
		return $constants[$level];
	}
}