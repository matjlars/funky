<?php
namespace funky\fields;

class password extends \funky\fields\field{
	public function init($args){
		$this->validators[] = function($val){
			if(empty($val)) return;
			if(strlen($val) != 32) return 'the password is not 32 characters long, which means it was not encrypted. you must encrypt your password, usually in your models update function.';
		};
	}

	// takes an unencrypted password and returns the encrypted password.
	// this does not set the value or change any state
	// NOTE: it's the model or controller's responsibility to encrypt the password.
	public function encrypt($val){
		return \md5($val);
	}

	public function dbtype(){
		return 'char(32)';
	}
}