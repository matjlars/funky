<?php
namespace funky\controllers\admin;
use models\image;

class images{
	public function __construct(){
		f()->access->enforce('admin');
		f()->template->view = 'admin';
	}

	public function index(){
		return f()->view->load('admin/images/index');
	}

	public function feed(){
		$images = image::query()->order('alt');
		return f()->view->load('admin/images/feed', [
			'images'=>$images,
		]);
	}

	public function edit($id=0){
		$image = image::fromid($id);

		if(!empty($_POST)){
			// handle file upload or re-upload
			$newFilenames = f()->uploads->handle('file');
			if(!empty($newFilenames)){
				$_POST['filename'] = $newFilenames[0];
			}

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

	public function delete(){
		if(empty($_POST['id'])) return 'no id given.';
		$image = image::fromid($_POST['id']);
		$image->delete();
		return 'ok';
	}

	// upload ajax endpoint (see admin.js/imagefield)
	public function upload(){
		$image = false;
		// attempt an image upload
		try{
			foreach(f()->uploads->handle('image') as $filename){
				$data = [
					'filename'=>$filename,
				];
				if(!empty($_POST['alt'])) $data['alt'] = $_POST['alt'];
				$image = image::insert($data);
			}
		}catch(\exception $e){
			http_response_code(400);
			die($e->getMessage());
		}

		if($image === false){
			http_response_code(400);
			die('Failed to upload image file.');
		}
		
		// return the new image id and url in the response
		die(json_encode(array(
			'id'=>$image->id,
			'url'=>$image->url(),
		)));
	}

	// used for the "images" field
	public function imagesfield_modal($image_id=0){
		// create/update
		if(!empty($_POST)){
			if(!empty($image_id)){
				$image = image::fromid($image_id);
				$image->update($_POST);
				return $image->id;
			}else{
				$images = image::upload('files', $_POST['alt']);

				$ids = [];
				foreach($images as $image){
					$ids[] = $image->id;
				}

				return implode(',', $ids);
			}
		}

		// load the modal
		$image = image::fromid($image_id);
		return f()->view->load('admin/images/imagesfield_modal', [
			'image'=>$image,
		]);
	}

	public function imagesfield_thumbnails(){
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