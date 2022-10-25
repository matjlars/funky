<?php
namespace funky\fields;
use models\image as imagemodel;

// $this->val is the image_id
class image extends \funky\fields\field{
	protected $image = null;
	
	public function init($args){
		$this->val = 0;
	}

	// returns an image model that this field references
	// if it doesn't reference any, it returns null
	public function get(){
		if(is_null($this->image)) $this->image = imagemodel::fromid($this->val);
		return $this->image;
	}

	// returns true or false, depending on if an image exists
	// if the image does not exist, calling get() will not give you anything useful.
	public function exists(){
		return $this->val > 0;
	}

	// returns the url for this image
	public function url(){
		return $this->get()->url();
	}

	// returns the alt text for this image
	public function alt(){
		return $this->get()->alt->get();
	}

	// returns a simple img tag for this image.
	public function tag(){
		return $this->get()->tag();
	}

	// takes an image model and sets the val to the image model id
	public function set($val){
		// any empty val will be set to reference 0
		if(empty($val)){
			$this->val = 0;
			$this->image = null;
			return;
		}

		// handle setting by id
		if(is_numeric($val)){
			$this->val = intval($val);
			$this->image = null;
			return;
		}

		// if it's an array, check if there's an 'id' and/or 'alt'
		if(is_array($val)){
			if(isset($val['image_id'])){
				$this->val = intval($val['image_id']);
				$this->image = null;

				if(!empty($val['alt'])){
					// load the image and update the alt
					$img = $this->get();
					$img->update(['alt'=>$val['alt']]);
				}
				return;
			}

			throw new \Exception('\\fields\\images::set() takes an array, but requires an "id" and optionally "alt" keys.');
		}

		// handle setting by image model:
		if(is_a($val, '\models\image')){
			$this->val = $val->id;
			$this->image = null;
			return;
		}

		// in this context, it's not an int or an image
		throw new \exception('\\fields\\image::set() takes either an image_id int or an image model object but you gave it a '.gettype($val));
	}

	public function dbtype(){
		return 'int unsigned';
	}

	// shortcut for getting the img tag
	public function __toString(){
		return $this->tag();
	}
}