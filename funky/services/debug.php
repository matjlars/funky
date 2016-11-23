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
		f()->load->view('errors/exception', array(
			'e'=>$e,
		));
	}
}