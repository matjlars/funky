<?php
namespace funky\controllers\admin;
use models\image;

class images extends \funky\basecontrollers\admintool{
	// upload ajax endpoint (see admin.js/imagefield)
	public function upload()
	{
		// attempt an image upload
		try{
			$filename = image::upload('image');
			$image = image::insert([
				'filename'=>$filename,
			]);
		}catch(\exception $e){
			http_response_code(400);
			die($e->getMessage());
		}
		
		// otherwise, return the new image id in the response
		die(json_encode(array(
			'id'=>$image->id,
			'url'=>$image->url(),
		)));
	}
}