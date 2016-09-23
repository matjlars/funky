<?php
class request extends j_service
{
	public function start()
	{
		j()->template->start('page');
	}
	public function perform()
	{
		j()->router->route();
	}
	public function stop()
	{
		j()->template->render();
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