<?php
class debug
{
	public function dump($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}
	public function exception($e)
	{
		echo 'error on website: '.$e->getMessage();
		if(f()->access->isadminadmin()){
			var_dump($e);
		}
	}
}