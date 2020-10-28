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
		return f()->view->load($this->path().'/index');
	}

	public function feed(){
		$modelname = $this->modelname().'s';
		$modelobjs = $this->get_feed_objects();

		return f()->view->load($this->path().'/feed', [
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
				f()->response->redirect('/'.$this->path().'/edit/'.$modelobj->id);
			}else{
				f()->flash->error('Error while saving: '.$modelobj->errormessage());
			}
		}
		$modelname = $this->modelname();
		return f()->view->load($this->path().'/edit', array(
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

	protected function path(){
		$class = get_called_class();
		$tokens = explode('\\', $class);

		// remove "controllers" from the beginning
		array_shift($tokens);

		return implode('/', $tokens);
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
