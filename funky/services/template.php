<?php

// This is an extremely usefule service that handles templating really well.
// Basically, you just say "j()->template->view = 'page';" at the top, then do any output, and it'll stick everything you output in a variable called "content" and pass it to your template.
// This works well for both loading a view from a controller or just using it at the top of a static page.
class template extends j_service
{
	private $data = array();
	private $view = 'page';
	private $head = '';
	
	public function __set($key,$value)
	{
		$this->data[$key] = $value;
	}
	public function __get($key)
	{
		if(isset($this->data[$key])) return $this->data[$key];
		return null;
	}
	
	// this function adds stuff into the <head> tag dynamically.
	// for example, pass this function an extra javascript file or css file you want to have inside the <head></head> tag.
	public function head($text)
	{
		$this->head .= $text;
	}
	
	public function start($view='')
	{
		$this->view = $view;
		ob_start();
	}
	public function render()
	{
		if(empty($this->view))
		{
			echo ob_get_clean();
		}
		else
		{
			$count = 1;
			$content = ob_get_clean();
			$content = str_replace('</head>',$this->head.'</head>',$content,$count);
			$this->data['content'] = $content;
			j()->load->view('templates/'.$this->view, $this->data);
		}
	}
}