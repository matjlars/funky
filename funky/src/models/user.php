<?php
namespace funky\models;
use core\model;

class user extends model
{
	public function hasrole(string $role)
	{
		return $this->field('roles')->in($role);
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