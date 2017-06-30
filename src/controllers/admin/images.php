<?php
namespace funky\controllers\admin;

use models\image;

class images
{
	public function __construct()
	{
		f()->access->enforce('admin');
		f()->template->view = 'admin';
	}
	public function upload()
	{
		// attempt an image upload
		try{
			$image = image::upload('image');
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