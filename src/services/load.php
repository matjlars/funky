<?php
namespace funky\services;

class load
{
	public function field($name, $typename, $args=array())
	{
		$class = '\\funky\\fields\\'.$typename;
		$field = new $class($name, $args);
		return $field;
	}
	public function fields($arr)
	{
		$f = array();
		foreach($arr as $a){
			$name = $a[0];
			$type = $a[1];
			$args = array();
			if(isset($a[2])) $args = $a[2];
			$f[$name] = f()->load->field($name, $type, $args);
		}
		return $f;
	}

	// returns the $_FILES array entries in a better format for handling multiple files.
	// the return value is an array of file arrays.
	// each file array contains "name", "type", "tmp_name", "error", and "size"
	// $name is the [name] attribute on the input[type=file] tag.
	// so $name is also the key in $_FILES
	// returns an empty array if there are no files.
	// returns an array with a single array if there is only one file.
	public function files($name){
		if(!isset($_FILES[$name])) return [];
		$arr = [];
		$files = $_FILES[$name];
		if(empty($files['name'])) return [];

		// handle a single file
		// wrap it in an array so you can always loop over the result.
		if(!is_array($files['name'])) return [$_FILES[$name]];

		$file_count = count($files['name']);
		if($file_count == 0) return [];
		$keys = array_keys($files);

		// copy the values over in the new format
		for($i = 0; $i < $file_count; $i++){
			foreach($keys as $key){
				$arr[$i][$key] = $files[$key][$i];
			}
		}

		return $arr;
	}
}