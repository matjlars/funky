<?php
namespace funky\fields;

class file extends field
{
	private $dir;
	private $extensions = null;
	private $uploaderror = '';
	
	public function url()
	{
		if(empty($this->val)) return '';
		return f()->url->get($this->dir.$this->val);
	}
	public function init($args)
	{
		// set or default the directory
		if(!empty($args['dir'])) $this->dir = $args['dir'];
		else $this->dir = 'uploads/';
		
		// remember allowed extensions
		if(!empty($args['extensions'])) $this->extensions = $args['extensions'];
		
		// validate based on any upload errors:
		$this->validators[] = function($val){
			if(!empty($this->uploaderror)){
				return $this->uploaderror;
			}
		};
		// validate based on extension:
		$this->validators[] = function($val){
			if(!is_null($this->extensions)){
				if(!in_array(pathinfo($val, PATHINFO_EXTENSION), $this->extensions)){
					return $val.' has an invalid extension. It must be one of: '.implode(', ',$this->extensions);
				}
			}
		};
	}
	public function upload()
	{
		// don't try to upload anything if there is nothing there
		if(empty($_FILES[$this->name()])) return;
		if(empty($_FILES[$this->name()]['name'])) return;
		
		// in this context, we want to upload the file
		$fulldir = f()->path->docroot($this->dir);
		$filedata = $_FILES[$this->name()];
		$target_file = $fulldir.basename($filedata['name']);
		$extension = pathinfo($target_file,PATHINFO_EXTENSION);
		
		// check extensions if any were given
		if(!is_null($this->extensions)){
			if(!in_array($extension, $this->extensions)){
				$this->uploaderror = $this->name().' must be one of the following types: '.implode(', ', $this->extensions).' but you gave it a '.$extension;
				return;
			}
		}
		
		// get a unique filename for this file
		$filepath = f()->unique->filename($this->dir.'/'.$filedata['name']);
		
		// if it gave up, don't save this file
		if($filepath === false){
			$this->uploaderror = 'Unable to find a unique filename for '.$this->name().'. Try changing the file name and uploading it again.';
			return;
		}
		
		// make sure the target directory exists
		if(!file_exists(dirname($filepath))) mkdir(dirname($filepath), 0777, true);
		
		// save this file to the server
		if(move_uploaded_file($filedata['tmp_name'], $filepath)){
			$this->val = basename($filepath);
		}else{
			$this->uploaderror = 'Unable to save your file ('.$filedata['name'].') to the server.';
		}
	}
	public function dbtype()
	{
		return 'varchar(255)';
	}
}