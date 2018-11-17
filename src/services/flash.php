<?php
namespace funky\services;

class flash
{
	// allows you to do something like f()->flash->success('you did it!');
	public function __call($type, $args)
	{
		if(!empty($args[0])){
			$this->addMessage($type, $args[0]);
		}
	}

	// returns all flash messages and deletes them from the session
	// they are organized by type, so it's an array of arrays.
	// the top-level array has keys of the type (either 'info', 'warning', or 'error')
	// the top-level array's values are arrays of strings
	public function pop()
	{
		$flash = $this->peek();
		unset(f()->session->flash);
		return $flash;
	}

	// returns all flash messages and does not destroy them from the session
	public function peek()
	{
		if(isset(f()->session->flash)) return f()->session->flash;
		return [];
	}

	private function addMessage($type, $msg)
	{
		// get the flash array
		if(isset(f()->session->flash)){
			$flash = f()->session->flash;
		}else{
			$flash = [];
		}

		// make sure this flash array has an array for this type
		if(!isset($flash[$type])) $flash[$type] = [];

		// add this message to this type array:
		$flash[$type][] = $msg;

		// save it in the session
		f()->session->flash = $flash;
	}
}