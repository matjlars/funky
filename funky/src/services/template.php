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
	
	// takes all of the buffered output, sticks it in the template, and returns it all as a string
	public function render($content)
	{
		if(empty($this->view) || f()->request->isxhr()){
			echo $content;
		}else{
			$this->data['content'] = $content;
			return f()->view->load('templates/'.$this->view, $this->data);
		}
	}
}