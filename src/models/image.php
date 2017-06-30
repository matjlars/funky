<?php
namespace funky\models;

class image extends \core\model
{
	const PATH = 'uploads/images/';
	
	public function url()
	{
		return f()->url->get(static::PATH.$this->filename);
	}
	public static function targetdir()
	{
		return f()->path->docroot(static::PATH);
	}
	public static function extensions()
	{
		return ['jpg','jpeg','gif','png','svg'];
	}
	// handles uploading an image and returns the new image object
	// this function will throw an exception with a better error if something doesn't quite work
	public static function upload($name)
	{
		if(empty($_FILES[$name])){
			throw new \exception('the image does not exist at all');
		}
		if(empty($_FILES[$name]['name'])){
			throw new \exception('the image does not have a name');
		}
		$validextensions = static::extensions();
		
		$filename = basename($_FILES[$name]['name']);
		$dir = static::targetdir();
		$extension = pathinfo($dir.$filename, PATHINFO_EXTENSION);
		
		// don't allow this upload if it is an invalid file extension
		if(!in_array($extension, $validextensions)){
			throw new \exception('The file you tried to upload was a .'.$extension.' but only one of these is permitted: ('.implode(', ', $validextensions).')');
		}
		
		// get a unique filename:
		$filename = static::uniquefilename($filename, $dir);
		
		// make sure there is a directory to upload into:
		if(!file_exists($dir)) mkdir($dir, 0777, true);
		
		// save it to the right spot:
		if(move_uploaded_file($_FILES[$name]['tmp_name'], $dir.$filename)){
			// create the file entry in the database and return that
			$image = image::insert([
				'filename'=>$filename,
			]);
			return $image;
		}else{
			// the file upload failed for some reason.
			throw new \exception('an error occurred while saving the file into its final location on the server');
		}
	}
	// returns a unique filename for a given filename and directory
	public static function uniquefilename($filename, $dir)
	{
		// it is probably already unique:
		if(!file_exists($dir.$filename)) return $filename;
		
		// find a unique filename:
		$basename = pathinfo($filename, PATHINFO_FILENAME);
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		//$dotpos = strrpos($filename, '.');
		$inc = 2;
		$maxinc = 10000;
		$tmp = $basename.'-'.$inc.'.'.$extension;
		while(file_exists($dir.$tmp)){
			// i guess make sure the server doesn't crash:
			if($inc > $maxinc) throw new \exception('filename '.$filename.' has no hope of being unique. there are already '.$maxinc.' files with the same name.');
			// try the next one:
			$inc = $inc + 1;
			$tmp = $basename.'-'.$inc.'.'.$extension;
		}
		return $tmp;
	}
	public static function fields()
	{
		return f()->load->fields([
			['filename', 'text'],
			['caption', 'text'],
			['alt', 'text'],
		]);
	}
}