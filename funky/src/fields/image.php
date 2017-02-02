<?php
namespace funky\fields;

// $this->val is the image_id
class image extends field
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
		if(is_null($this->image)) $this->image = \models\image::fromid($this->val);
		return $this->image;
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
		return $image->alt();
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
		return 'int(11)';
	}
}