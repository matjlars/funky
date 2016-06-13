<?php
class request
{
	public function start()
	{
		j()->template->start('page');
	}
	public function perform()
	{
		j()->router->router();
	}
	public function stop()
	{
		j()->template->render();
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