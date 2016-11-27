<?php
namespace funky\services;

class request
{
	public function perform()
	{
		try{
			f()->template->start('page');
			f()->router->route();
			f()->template->render();
			exit(0);
		}catch(Exception $e){
			f()->template->cancel();
			f()->debug->exception($e);
			exit(1);
		}
	}
	public function isxhr()
	{
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}
		return false;
	}
}