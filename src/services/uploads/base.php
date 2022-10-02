<?php
namespace funky\services\uploads;

abstract class base{
	public abstract function put($filename, $content);
	public abstract function get($filename);
	public abstract function url($filename);
	public abstract function delete($filename);
	public abstract function exists($filename);
	public abstract function all();
	public abstract function search($query);

	// takes a filename, returns a unique version of that filename.
	public function unique($filename){
		// sanitize the filename:
		$filename = f()->format->filename($filename);

		// it might already be unique:
		if($this->exists($filename) === false){
			return $filename;
		}

		// find a unique filename:
		$basename = pathinfo($filename, PATHINFO_FILENAME);
		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$inc = 2;
		$maxinc = 1000;
		$tmp = $basename.'-'.$inc.'.'.$extension;
		while($this->exists($tmp)){
			// i guess make sure the server doesn't crash:
			if($inc > $maxinc) throw new \exception('filename '.$filename.' has no hope of being unique. there are already '.$maxinc.' files with the same name.');

			// try the next one:
			$inc = $inc + 1;
			$tmp = $basename.'-'.$inc.'.'.$extension;
		}
		return $tmp;
	}

	// given the key to the $_FILES array,
	// this handles uploading the file into the right spot.
	// returns an array of new filenames
	// throws an exception with a user-readable error message if ALL files err out.
	// if 1 file uploads and another file errs out, this will not throw an exception
	// this will only return successfully uploaded filenames
	// the reason this returns an array is because it handles both single and multiple uploads
	public function handle($key){
		// consolidate file/files into similar format
		$files = f()->load->files($key);

		// if no files were uploaded, we're done here.
		if(empty($files)){
			return [];
		}

		$finalFilenames = [];
		$errors = [];

		foreach($files as $file){
			// check for basic php file upload errors:
			if(isset($file['error'])){
				$err = f()->debug->file_upload_error($file['error']);
				if($err !== false){
					$errors[] = $err;
					continue;
				}
			}

			if(empty($file['name'])){
				$errors[] = 'This file has no name.';
				continue;
			}

			// sanitize the filename
			$filename = f()->format->filename(basename($file['name']));

			try{
				// get a unique filename
				$filename = f()->uploads->unique($filename);

				// put the file in the uploads place
				$content = file_get_contents($file['tmp_name']);
				f()->uploads->put($filename, $content);
				$finalFilenames[] = $filename;
			}catch(\Exception $e){
				$errors[] = $e->getMessage();
				continue;
			}
		}

		// throw an exception if none of the files finished uploading,
		// and if there are errors.
		if(empty($finalFilenames) && !empty($errors)){
			$errorCount = count($errors);
			$fileCount = count($files);
			throw new \Exception(count($errors).' error'.(($errorCount==1) ? '' : 's').' while trying to upload file'.(($fileCount==1) ? '' : 's').': '.implode(', ', $errors));
		}

		// no errors. yay!
		return $finalFilenames;
	}
}
