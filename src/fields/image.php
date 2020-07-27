<?php
namespace funky\fields;
use models\image as imagemodel;

// $this->val is the image_id
class image extends \funky\fields\field
{
	private $image = null;
	
	public function init($args)
	{
		// default to 0
		$this->val = 0;
	}

	// returns an image model that this field references
	// if it doesn't reference any, it returns null
	public function get()
	{
		if(is_null($this->image)) $this->image = imagemodel::fromid($this->val);
		return $this->image;
	}

	// returns true or false, depending on if an image exists
	// if the image does not exist, calling get() will not give you anything useful.
	public function exists()
	{
		return $this->val > 0;
	}

	// returns the url for this image
	public function url()
	{
		$image = $this->get();
		return $image->url();
	}

	// returns the alt text for this image
	public function alt()
	{
		$image = $this->get();
		return $image->alt->get();
	}

	// returns a simple img tag for this image.
	public function tag()
	{
		$img = $this->get();
		return '<img src="'.$img->url().'" alt="'.$img->alt->get().'">';
	}

	// takes an image model and sets the val to the image model id
	public function set($val)
	{
		// any empty val will be set to reference 0
		if(empty($val)){
			$this->val = 0;
			return;
		}
		// handle setting by id
		if(is_numeric($val)){
			$this->val = intval($val);
			return;
		}
		// handle setting by image model:
		if(is_a($val, '\models\image')){
			$this->val = $val->id;
			return;
		}
		// in this context, it's not an int or an image
		throw new \exception('\\fields\\image::set() takes either an image_id int or an image model object but you gave it a '.gettype($val));
	}

	public function dbtype()
	{
		return 'int(11) unsigned';
	}
}