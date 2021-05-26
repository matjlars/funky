<?php
namespace funky\models;

class user extends \funky\model
{
	public function hasrole($role){
		return $this->roles->in($role);
	}

	public function update($data){
		// if we have a new password to store, encrypt it:
		if(!empty($data['password'])) $data['password'] = $this->password->encrypt($data['password']);
		parent::update($data);
	}

	public static function fields(){
		$roles = static::getAllRoles();

		return f()->load->fields([
			['email', 'text'],
			['password', 'password'],
			['roles', 'set', ['values'=>$roles]],
		]);
	}

	// override this if you want more roles
	protected static function getAllRoles(){
		return ['dev', 'admin'];
	}
}
