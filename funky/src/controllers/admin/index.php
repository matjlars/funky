<?php
namespace funky\controllers\admin;

class index
{
	public function index()
	{
		if(!$this->dbsetup()) return;
		if(!$this->userstableexists()) return;
		if(!$this->ensureuserexists()) return;
		
		// first of all, ensure there is a "users" table, otherwise the entire admin area makes no sense.
		try{
			if(!f()->db->table_exists('users')){
				throw new \exception('the "users" database table does not exist.');
			}
		}catch(\exception $e){
			f()->load->view('admin/index/dbsetup', array(
				'message'=>$e->getMessage(),
			));
			return;
		}
		
		// the db and user table is set up, so continue
		f()->access->enforce();
		f()->template->view = 'admin';
		f()->load->view('admin/index/index');
	}
	// ensures and helps set up the database.
	// returns true if the database is set up and ready to use.
	private function dbsetup()
	{
		try{
			$db = f()->db;
		}catch(\exception $e){
			if(f()->request->method() == 'POST'){
				// set up the config file
				throw new \exception('todo make the config editable and utilize that here');
			}
			f()->load->view('admin/index/dbsetup', array(
				'message'=>$e->getMessage(),
			));
			return false;
		}
		return true;
	}
	// ensures the users table is set up
	// returns true if the users table is set up
	private function userstableexists()
	{
		if(!f()->db->table_exists('users')){
			// get users migrations
			$sql = f()->migrations->create_table_sql('\\models\\user');
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				f()->db->query($sql);
				return true;
			}
			f()->load->view('admin/index/userstableexists', array(
				'sql'=>$sql,
			));
			return false;
		}
		// the users table exists
		return true;
	}
	// ensures there is at least 1 user.
	// returns true if there is at least 1 user.
	private function ensureuserexists()
	{
		// see if there are any already
		$usercount = \models\user::count();
		if($usercount > 0) return true;
		
		// in this context, there are no users
		if(empty($_POST)){
			$user = new \models\user();
			// default to adminadmin because it's the first user
			$user->roles = 'adminadmin,admin';
			f()->load->view('admin/index/ensureuserexists', array(
				'user'=>$user,
			));
		}else{
			$user = \models\user::insert($_POST);
			return true;
		}
		return false;
	}
}