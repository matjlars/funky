<?php
namespace funky\models;

// this class is only here to serve as a base class for your own image model.
// this file doesn't actually count as a model.
// see how to use it in scaffold/src/models/image.php
class image extends \funky\model{
	public function markdown_snippet(){
		return '[img.'.$this->id.']';
	}

	public function tag(){
		return '<img src="'.$this->url().'" alt="'.$this->alt.'">';
	}

	// shortcut for printing image as a tag
	public function __toString(){
		return $this->tag();
	}
	
	public function url(){
		return f()->uploads->url($this->filename);
	}

	// create new image model(s) from the $_FILES array.
	// handles uploading single or multiple image files.
	// returns an array of new image models.
	// throws an exception if there are no successful uploads
	public static function upload($name, $alt=''){
		$models = [];

		// create an image model for each file
		foreach(f()->uploads->handle($name) as $filename){
			$models[] = static::insert([
				'name'=>$filename,
				'filename'=>$filename,
				'alt'=>$alt,
			]);
		}

		return $models;
	}

	public static function fields(){
		return f()->load->fields([
			['name', 'text'],
			['filename', 'text'],
			['alt', 'text', ['label'=>'Alt Text']],
		]);
	}
}