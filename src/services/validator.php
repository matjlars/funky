<?php
namespace funky\services;

// each function in this class will return TRUE if it's all good to go, and FALSE if it isn't.
// if the function is passed a TRUE, that means that function will run
class validator
{
	public function dbconfig($run=false)
	{
		if($run){
			throw new \exception('the validator->dbconfig() cant set the db config because config is not writable. please set it in the files.');
		}
		try{
			foreach(['db_server', 'db_user', 'db_password', 'db_name'] as $key){
				f()->config->$key;
			}
			// in this context, the config service didn't complain at all, so we have all those config settings set.
			return true;
		}catch(\exception $e){
			// in this context, the config service complained, so the values are not set up.
			return false;
		}
	}
}
