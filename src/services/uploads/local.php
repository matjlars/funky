<?php
namespace funky\services\uploads;

class local extends base{
	// the full path to the directory where all upload files exist
	// this ends with a slash for convenience
	protected $dir;

	public function __construct(){
		$subdir = 'uploads';
		if(isset(f()->config->uploads_dir)) $subdir = f()->config->local_uploads_dir;
		$this->dir = f()->path->docroot($subdir).'/';

		// make sure the uploads dir exists
		if(!file_exists($this->dir)){
			set_error_handler(function($errno, $errstr){
				throw new \Exception('Failed to create directory "'.$this->dir.'": '.$errstr);
			}, E_WARNING);
			mkdir($this->dir, 0777, true);
			restore_error_handler();
		}
	}

	public function put($filename, $content){
		$path = $this->dir.$filename;
		file_put_contents($path, $content);
	}
	
	public function get($filename){
		$path = $this->dir.$filename;
		return file_get_content($path);
	}

	public function url($filename){
		return f()->url->get($this->dir.$filename);
	}

	public function delete($filename){
		$path = $this->dir.$filename;
		unlink($path);
	}

	public function exists($filename){
		$path = $this->dir.$filename;
		return file_exists($path);
	}

	public function all(){
		$all = [];
		foreach(scandir($this->dir) as $f){
			if($f == '.') continue;
			if($f == '..') continue;
			if(is_dir($f)) continue;
			$all[] = basename($f);
		}
		return $all;
	}

	// *.txt will return all .txt files
	public function search($query){
		$files = [];
		foreach(glob($this->dir.$query) as $f){
			$files = basename($f);
		}
		return $files;
	}
}