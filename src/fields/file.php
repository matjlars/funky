<?php
namespace funky\fields;

class file extends \funky\fields\field{
	private $extensions = null;
	
	public function url(){
		if(empty($this->val)) return '';
		return f()->uploads->url($this->val);
	}

	public function init($args){
		if(!empty($args['extensions'])) $this->extensions = $args['extensions'];
	}

	public function after_update(){
		$this->upload();
	}

	// attempts the file upload.
	// sets $this->val to the new filename if successful.
	// returns false on success
	// returns an error message on fail
	public function upload(){
		if(empty($_FILES[$this->name()])) return 'No file given. Try selecting a file first.';

		// check extensions if any were given
		if(!is_null($this->extensions)){
			$extension = strtolower(pathinfo($_FILES[$this->name()]['name'], PATHINFO_EXTENSION));
			if(!in_array($extension, $this->extensions)){
				return $this->name().' must be one of the following types: '.implode(', ', $this->extensions).' but you gave it a '.$extension;
			}
		}

		// try to upload it
		try{
			$filenames = f()->uploads->handle($this->name());
			if(empty($filenames)){
				return 'No file given. Try selecting a file first.';
			}

			$this->val = $filenames[0];
			return false;
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}

	public function dbtype(){
		return 'varchar(255)';
	}
}