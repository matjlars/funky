<?php
// if you want basic admin tool functionality, make a controller that extends this class.
// check out the protected functions in this file to see what you can override to make it work for your model
// the public functions here are the endpoints it's exposing

namespace funky\basecontrollers;
class admintool{
	public function __construct(){
		f()->access->enforce('admin');
		f()->template->view = 'admin';
	}

	public function index(){
		return f()->view->load($this->view_path().'/index');
	}

	public function feed(){
		$modelname = $this->modelname().'s';
		$modelobjs = $this->get_feed_objects();

		return f()->view->load($this->view_path().'/feed', [
			$modelname=>$modelobjs,
		]);
	}

	public function edit($id=0){
		$modelclass = $this->modelclass();
		$modelobj = $modelclass::fromid($id);
		if(!empty($_POST)){
			$this->update($modelobj, $_POST);
			if($modelobj->isvalid()){
				f()->flash->success('Saved!');
				f()->response->redirect('/'.$this->url_path().'/edit/'.$modelobj->id);
			}else{
				f()->flash->error('Error while saving: '.$modelobj->errormessage());
			}
		}
		$modelname = $this->modelname();
		return f()->view->load($this->view_path().'/edit', array(
			$modelname=>$modelobj,
		));
	}

	public function deactivate(){
		$modelclass = $this->modelclass();

		// make sure the model has an "isactive" field
		$canactivate = false;
		foreach($modelclass::fields() as $fielddef){
			if($fielddef->name() == 'isactive') $canactivate = true;
		}
		if($canactivate == false) return null;

		if(empty($_POST['id'])) return 'no id given';
		$obj = $modelclass::fromid($_POST['id']);
		$obj->update(['isactive'=>false]);

		$error = $obj->errormessage();
		if(empty($error)) return 'ok';
		return $error;
	}

	public function activate(){
		$modelclass = $this->modelclass();

		// make sure the model has an "isactive" field
		$canactivate = false;
		foreach($modelclass::fields() as $fielddef){
			if($fielddef->name() == 'isactive') $canactivate = true;
		}
		if($canactivate == false) return null;

		if(empty($_POST['id'])) return 'no id given';
		$obj = $modelclass::fromid($_POST['id']);
		$obj->update(['isactive'=>true]);

		$error = $obj->errormessage();
		if(empty($error)) return 'ok';
		return $error;
	}

	public function delete(){
		$modelclass = $this->modelclass();

		if(empty($_POST['id'])) return 'no id given';
		$obj = $modelclass::fromid($_POST['id']);
		if($obj->exists()) $obj->delete();

		// regardless of if the delete succeeded, this thing does not exist now.
		return 'ok';
	}

	// ajax endpoint the slug field uses
	public function generateslug(){
		$modelclass = $this->modelclass();
		$table = $modelclass::table();
		$slug = f()->format->slug($_POST['val']);
		$slug = f()->unique->dbval($table, 'slug', $slug);
		return $slug;
	}


	// ajax endpoint to save sort_id
	public function sort()
	{
		if(empty($_POST['ids'])) throw new \Exception('no ids given');

		$modelclass = $this->modelclass();

		$sort_id = 1;
		foreach($_POST['ids'] as $id){
			$model = $modelclass::fromid($id);
			$model->update(['sort_id'=>$sort_id++]);
		}

		return 'Sorted.';
	}

	// ajax endpoint for the bridge_field js
	// this is for if a user is trying to find these
	// for the purposes of a bridge table.
	// the response format is a JSON object mapping id: name
	// where "name" actually just means a human readable version of the record.
	public function bridge_ajax(){
		$modelclass = $this->modelclass();
		$records = $modelclass::search($_GET);

		// generate map from id to label
		$data = [];
		foreach($records as $r){
			$data[$r->id] = $r->bridge_label();
		}

		f()->response->json($data);
	}

	// file download endpoint for exporting data to a CSV file.
	public function export(){
		$modelclass = $this->modelclass();
		
		// start creating the export data array
		$data = [];
		$data[] = $modelclass::export_headers();

		// add a row for eaach export record
		foreach($this->get_export_records() as $r){
			$data[] = $r->export_data();
		}

		// send the csv file to the browser
		$table_name = $modelclass::table();
		f()->response->csv($data, $table_name.'.csv');
	}

	public function import(){
		$modelname = $this->modelname();
		$modelclass = $this->modelclass();
		$headers = $modelclass::import_headers();

		// download a template file
		if(!empty($_GET['download_template'])){
			f()->response->csv([$headers], $modelname.'-import.csv');
		}

		if(!empty($_FILES['file'])){
			$f = fopen($_FILES['file']['tmp_name'], 'r');
			if($f === false) die('unable to open uploaded file.');

			// keys are all the field names
			$model_fields = array_flip($headers);

			// maps the column index to the field name
			$csv_headers = null;

			// go through each row in the csv
			$insert_count = 0;
			while(($row = fgetcsv($f, 10000, ',')) !== false){
				if(is_null($csv_headers)){ // the first row
					$csv_headers = $row;
				}else{ // not the first row
					$new_model_data = [];
					foreach($row as $col_idx=>$val){
						$field_name = $csv_headers[$col_idx];
						$new_model_data[$field_name] = $val;
					}
					$modelclass::insert($new_model_data);
					$insert_count++;
				}
			}

			if(empty($insert_count)){
				f()->flash->error('There were no rows to insert in the CSV file.');
			}else{
				f()->flash->success('Successfully imported '.$insert_count.' row'.(($insert_count==1) ? '' : 's').'!');
			}
		}

		// display the import page
		return f()->view->load('basecontrollers/admintool/import', [
			'headers'=>$headers,
			'modelname'=>$modelname,
			'path'=>$this->url_path(),
		]);
	}

	protected function get_export_records(){
		$modelclass = $this->modelclass();
		if(empty($_GET)){
			// no params, so grab all records
			return $modelclass::query();
		}else{
			return $modelclass::search($_GET);
		}
	}

	protected function url_path(){
		$class = get_called_class();
		$tokens = explode('\\', $class);

		// remove "controllers" from the beginning
		array_shift($tokens);

		return implode('/', $tokens);
	}

	protected function view_path(){
		return $this->url_path();
	}

	// returns a fully namespaced model class name to use for this admin tool
	// if this function is incorrect, consider renaming your model or overriding this function.
	protected function modelclass(){
		$modelname = $this->modelname();
		return "\\models\\$modelname";
	}

	// returns a singular noun version of the model name
	protected function modelname(){
		$class = get_called_class();
		$lastslash = strrpos($class, '\\');
		$last = substr($class, $lastslash+1);
		$last = rtrim($last, 's');
		return $last;
	}

	// returns a modelquery for which records to show in the feed.
	protected function get_feed_objects()
	{
		// either get all or filter by post params:
		$modelclass = $this->modelclass();
		if(empty($_POST)){
			$modelobjs = $modelclass::query();
		}else{
			$modelobjs = $modelclass::search($_POST);
		}

		// order by sort_id if there is a sort_id
		if($modelclass::has_field('sort_id')){
			$modelobjs->orderby('sort_id');
		}

		return $modelobjs;
	}

	// override this if you want to do something after update
	// like update some associations or whatever.
	// make sure to call parent::update($modelobj, $data); so the actual update happens when you want.
	protected function update($modelobj, $data)
	{
		$modelobj->update($data);
	}
}
