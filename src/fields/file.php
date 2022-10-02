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

	public function before_update(){
		$error = $this->upload();
		if($error){
			f()->flash->error($error);
		}
	}

	public function before_delete(){
		if(!empty($this->val)){
			f()->uploads->delete($this->val);
		}
	}

	// attempts the file upload.
	// sets $this->val to the new filename if successful.
	// returns false on success
	// returns an error message on fail
	protected function upload(){
		// don't do anything if no file was uploaded
		if(empty($_FILES[$this->name()]) || empty($_FILES[$this->name()]['name'])){
			return false;
		}

		// check extensions
		if(!is_null($this->extensions)){
			$extension = strtolower(pathinfo($_FILES[$this->name()]['name'], PATHINFO_EXTENSION));
			if(!in_array($extension, $this->extensions)){
				throw new \Exception($this->name().' must be one of the following types: '.implode(', ', $this->extensions).' but you gave it a '.$extension);
			}
		}

		// try to upload it
		try{
			$filenames = f()->uploads->handle($this->name());
			if(empty($filenames)){
				throw new \Exception($this->name().' failed to upload. Please try again.');
			}

			// remember the new filename
			// this works because it's in before_update
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