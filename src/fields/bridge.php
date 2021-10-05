<?php
namespace funky\fields;

class bridge extends \funky\fields\field{
	// $this->val is an array of associated ids

	protected $model;
	protected $bridge;
	protected $other_model;
	protected $ajax_path;
	protected $other_id_col;
	protected $id_col;

	public function init($args){
		if(empty($args['bridge'])) throw new \Exception('"bridge" missing. It should be name of the bridge table.');
		$this->bridge = $args['bridge'];

		if(empty($args['other_model'])) throw new \Exception('"other_model" missing. It should be the name of the other model class.');
		$this->other_model = $args['other_model'];

		// set or guess at other_id_col
		if(empty($args['other_id_col'])){
			$this->other_id_col = $args['other_model'].'_id';
		}else{
			$this->other_id_col = $args['other_id_col'];
		}

		// set or guess the ajax_path
		if(empty($args['ajax_path'])){
			$this->ajax_path = '/admin/'.$args['other_model'].'s/bridge_ajax';
		}else{
			$this->ajax_path = $args['ajax_path'];
		}

		$this->val = [];
	}

	public function init_model($model){
		$this->model = $model;
		$this->id_col = $this->this_class().'_id';
	}

	// returns a ModelQuery of associated records for the given id
	public function get(){
		$c = $this->other_class(false);
		$ids = $this->get_ids();

		// return a modelquery with no records if there are no associations
		if(empty($ids)) return $c::query()->where('false');

		// load other models with those ids
		return $c::query()->where('id IN ('.implode(',', $ids).')');
	}

	// returns an array of ids for associations, given the model's $id.
	// returns an empty array if there are no associations.
	public function get_ids(){
		if(empty($this->model->id)) return [];
		return f()->db->query('SELECT '.$this->other_id_col.' FROM '.$this->bridge.' WHERE '.$this->id_col.' = '.$this->model->id)->arr($this->other_id_col);
	}

	public function set($ids){
		if(empty($ids)){
			$this->val = [];
			return;
		}

		if(is_string($ids)){
			$this->val = explode(',', $ids);
			return;
		}

		if(is_array($ids)){
			$this->val = $ids;
			return;
		}

		$this->val = [];
	}

	public function after_update(){
		$this->save();
	}

	public function save(){
		// get an array of all the already-associated ids
		$existing_ids = f()->db->query("SELECT `$this->other_id_col` FROM `$this->bridge` WHERE `$this->id_col` = {$this->model->id}")->arr($this->other_id_col);

		// insert these $ids for $this->id if they don't exist already
		foreach($this->val as $id){
			$idx = array_search($id, $existing_ids);
			if($idx === false){
				// this id is new, so insert it:
				f()->db->insert($this->bridge, [
					$this->id_col=>$this->model->id,
					$this->other_id_col=>$id,
				]);
			}
			// else, this id needs to exist and already does exist, so do nothing.

			// delete it from existing_ids so at the end,
			// we are left with a list of IDs to delete.
			unset($existing_ids[$idx]);
		}

		// delete associations that exist for $this->id that aren't in $ids
		foreach($existing_ids as $old_id){
			f()->db->query("DELETE FROM `$this->bridge` WHERE `$this->id_col` = {$this->model->id} AND `$this->other_id_col` = $old_id");
		}
	}

	// this isn't super useful but it's more useful than an exception
	public function __toString(){
		return strval($this->val);
	}

	public function dbtype(){
		// this means there isn't actually a db column for this field.
		// this field type is a helper for working with bridge tables.
		return null;
	}

	public function dbval(){
		throw new \Exception('There is no dbval for bridge fields.');
	}

	// returns the other model's class name as a string.
	protected function other_class($strip_namespace=true){
		if($strip_namespace){
			return $this->other_model;
		}else{
			return '\\models\\'.$this->other_model;
		}
	}

	// returns the name of the class for the model this field is on.
	protected function this_class($strip_namespace=true){
		$full_class = get_class($this->model);
		if($strip_namespace){
			return substr($full_class, strrpos($full_class, '\\') + 1);
		}else{
			return $full_class;
		}
	}

	// returns the ajax_path to use for searching for records.
	// the path here should return a JSON response that is an object
	// that maps the record IDs to the human readable label for that thing.
	// it should accept 2 GET params,
	//   q: the search query a user is entering
	//   ids: a CSV of IDs to filter on. used for updating the tags
	public function ajax_path(){
		return $this->ajax_path;
	}
}