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
		$modelclass = $this->modelclass();
		$modelobjs = $modelclass::query();
		$modelname = $this->modelname().'s';
		return f()->view->load($this->path().'/feed', [
			$modelname=>$modelobjs,
		]);
	}

	public function edit($id=0){
		$modelclass = $this->modelclass();
		$modelobj = $modelclass::fromid($id);
		if(!empty($_POST)){
			$modelobj->update($_POST);
			if($modelobj->isvalid()){
				f()->response->redirect('/'.$this->path());
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
}
