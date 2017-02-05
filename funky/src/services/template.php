<?php
namespace funky\services;

class template
{
	private $data = array();
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
	
	public function start()
	{
		ob_start();
	}
	public function cancel()
	{
		// get rid of all output buffers
		while(ob_get_level() > 0) ob_end_clean();
	}
	// takes all of the buffered output, sticks it in the template, and returns it all as a string
	public function render()
	{
		if(empty($this->view) || f()->request->isxhr()){
			ob_end_flush();
		}else{
			$this->data['content'] = ob_get_clean();
			f()->view->load('templates/'.$this->view, $this->data);
		}
	}
}