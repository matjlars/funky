<?php
namespace funky\controllers\admin;

class index{
	public function __construct(){
		f()->template->view = 'admin';
	}

	public function index(){
		// validate all of the things
		// if any of these functions returns false, that means they were not needed
		foreach(['dbsetup','userstableexists','ensureuserexists'] as $func){
			$response = $this->$func();
			if(!empty($response)) return $response;
		}
		
		// the db and user table is set up, so continue
		f()->access->enforce();
		return f()->view->load('admin/index/index');
	}

	private function dbsetup(){
		// if we don't have any of the config values we need, show the form to add them easily
		if(!isset(f()->config->db_name) || !isset(f()->config->db_user) || !isset(f()->config->db_password)){
			if(f()->request->method() == 'POST'){
				// save all the config values
				$allset = true;
				foreach(['db_name','db_user','db_password'] as $key){
					$val = $_POST[$key];
					if(empty($val)) $allset = false;
					f()->config->$key = $val;
				}
				if($allset) return false;
			}
			return f()->view->load('admin/index/dbsetup');
		}
	}

	// ensures the users table is set up
	// returns true if the users table is set up
	private function userstableexists(){
		if(!f()->db->table_exists('users')){
			// get users migrations
			$sql = f()->migrations->create_table_sql('\\models\\user');
			if(isset($_POST['createusers'])){
				f()->db->query($sql);
				return false;
			}
			return f()->view->load('admin/index/userstableexists', [
				'sql'=>$sql,
			]);
		}
	}

	// ensures there is at least 1 user.
	// returns true if there is at least 1 user.
	private function ensureuserexists(){
		// see if there are any already
		$usercount = \models\user::count();
		if($usercount > 0) return false;
		
		// in this context, there are no users
		if(!isset($_POST['email']) || !isset($_POST['password'])){
			$user = new \models\user();
			// default to dev because it's the first user
			$user->roles = 'dev,admin';
			return f()->view->load('admin/index/ensureuserexists', [
				'user'=>$user,
			]);
		}else{
			$user = new \models\user();
			$user->update($_POST);
			// also log this user in right now
			f()->access->login($_POST['email'], $_POST['password']);
		}
	}
}