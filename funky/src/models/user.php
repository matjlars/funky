<?php
namespace funky\models;
use core\model;

class user extends model
{
	public function hasrole($role)
	{
		return $this->roles->in($role);
	}
	public static function fields()
	{
		return f()->load->fields([
			['email', 'text'],
			['password', 'password'],
			['roles', 'set', ['values'=>['adminadmin', 'admin']]],
		]);
	}
}