<?php
namespace funky\services;

class uploads extends \funky\facade_service{
	public function __construct(){
		// load s3 if all config vars needed are specified:
		if(isset(f()->config->s3_key) && isset(f()->config->s3_secret)){
			$this->service = $this->load('s3');
			return;
		}

		// default to local filesystem
		$this->service = $this->load('local');
	}
}