<?php
class request
{
	public function start()
	{
		f()->template->start('page');
	}
	public function perform()
	{
		f()->router->route();
	}
	public function stop()
	{
		f()->template->render();
		exit;
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