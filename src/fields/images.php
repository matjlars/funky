<?php
namespace funky\fields;

// use this field if you want a quick way to attach multiple images to something
// it stores it as a CSV in a TEXT field, so don't use this if you want to query which images are being used here.
class images extends \funky\fields\field
{
	// returns a modelquery of image records
	// or an empty array if there aren't any images.
	public function get()
	{
		if(empty($this->val)) return [];
		return \models\image::query()->where('id IN ('.$this->dbval().')');
	}

	// returns an array of image_ids
	public function get_ids()
	{
		return $this->val;
	}

	// returns true if there are no images
	public function empty()
	{
		return empty($this->val);
	}

	// accepts a csv string of image_ids, or an array of ids
	// $this->val is an array of image_ids
	public function set($val)
	{
		if(empty($val)){
			$this->val = [];
		}elseif(is_string($val)){
			$this->val = explode(',', $val);
		}elseif(is_array($val)){
			$this->val = $val;
		}else{
			throw new \Exception('images::set() only accepts a csv of image_ids or an array of image_ids.');
		}
	}

	public function dbval()
	{
		return implode(',', $this->val);
	}

	public function dbtype()
	{
		return 'text';
	}
}