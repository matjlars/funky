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
		$images = image::query()->orderby('alt');
		return f()->view->load('admin/images/feed', [
			'images'=>$images,
		]);
	}

	public function edit($id=0){
		$image = image::fromid($id);

		if(!empty($_POST)){
			$image->update($_POST);
			if($image->isvalid()){
				f()->flash->success('Saved!');
				f()->response->redirect('/admin/images/edit/'.$image->id);
			}
		}

		return f()->view->load('admin/images/edit', [
			'image'=>$image,
		]);
	}

	// upload ajax endpoint (see admin.js/imagefield)
	public function upload()
	{
		// attempt an image upload
		try{
			$data = [];
			$data['filename'] = image::upload('image');
			if(!empty($_POST['alt'])) $data['alt'] = $_POST['alt'];

			$image = image::insert($data);
		}catch(\exception $e){
			http_response_code(400);
			die($e->getMessage());
		}
		
		// return the new image id and url in the response
		die(json_encode(array(
			'id'=>$image->id,
			'url'=>$image->url(),
		)));
	}

	// used for the "images" field
	public function imagesfield_modal($image_id=0)
	{
		$image = image::fromid($image_id);

		// create/update
		if(!empty($_POST)){
			$image->update($_POST);
			return $image->id;
		}

		return f()->view->load('admin/images/imagesfield_modal', [
			'image'=>$image,
		]);
	}

	public function imagesfield_thumbnails()
	{
		if(!empty($_POST['image_ids'])){
			$images = image::query()->where('id IN ('.$_POST['image_ids'].')');
		}else{
			// can't just return '' because that means 404. so we can just do this instead:
			f()->response->send(200, '');
		}

		return f()->view->load('admin/images/imagesfield_thumbnails', [
			'images'=>$images,
		]);
	}
}