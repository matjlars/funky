<?php
namespace funky\models;

class user extends core\model
{
	public function fields()
	{
		return f()->load->fields([
			['email', 'text'],
			['password', 'password'],
			['roles', 'set', ['values'=>['adminadmin', 'admin']]],
		]);
	}
}