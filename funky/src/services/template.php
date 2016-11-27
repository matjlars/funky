<?php
namespace funky\services;

class template
{
	private $data = array();
	private $head = '';
	public $view = 'page';
	
	public function __set($key,$value)
	{
		$this->data[$key] = $value;
	}
	public function __get($key)
	{
		if(isset($this->data[$key])) return $this->data[$key];
		return null;
	}
	public function exception($e)
	{
		echo 'error on website: '.$e->getMessage();
		if(f()->access->isadminadmin()){
			var_dump($e);
		}
	}
	
	public function start()
	{
		ob_start();
	}
	public function cancel()
	{
		ob_end_clean();
	}
	public function render()
	{
		if(empty($this->view) || f()->request->isxhr())
		{
			ob_end_flush();
		}
		else
		{
			$this->data['content'] = ob_get_clean();
			f()->load->view('templates/'.$this->view, $this->data);
		}
	}
}