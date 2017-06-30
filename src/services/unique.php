<?php
namespace funky\services;

// provides some handy methods for getting unique things
class unique
{
	// given a full filepath (including the filename), this function returns a new unique value
	// for example, use this when uploading a file to ensure the filename is unique
	// returns false if it cannot find a unique name
	public function filename($filepath)
	{
		// try to add "2" etc. to the end of the filename
		$pathinfo = pathinfo($filepath);
		$num = 2;
		while(file_exists($filepath)){
			$filepath = $pathinfo['dirname'].'/'.$pathinfo['filename'].$num.'.'.$pathinfo['extension'];
			$num++;
			if($num > 1000) return false;
		}
		return $filepath;
	}
}