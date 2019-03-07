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

	public function index()
	{
		return f()->view->load('admin/images/index');
	}

	public function feed()
	{
		$images = image::query();
		return f()->view->load('admin/images/feed', [
			'images'=>$images,
		]);
	}

	public function edit($id=0){
		if(empty($id)){
			f()->flash->error('Unable to create a new image this way');
			f()->response->redirect('/admin/images');
		}

		$image = image::fromid($id);
		if(!empty($_POST)){
			$image->update($_POST);
			if($image->isvalid()){
				f()->flash->success('Saved!');
				f()->response->redirect('/admin/images/edit/'.$image->id);
			}
		}

		return f()->view->load('/admin/images/edit', [
			'image'=>$image,
		]);
	}

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