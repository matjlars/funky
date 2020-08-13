<?php
namespace funky\services;

class template
{
	private $data = array();
	private $csspaths = array();
	private $jspaths = array();
	private $canonicalPath = '';
	private $extra_head_tags = [];
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

	public function __isset($key)
	{
		return isset($this->data[$key]);
	}

	// tells the template to add a css file to the head tags
	// given a filepath relative to the docroot (i.e. 'css/styles.css')
	public function css($filepath)
	{
		// don't add it if it's already in there
		if(in_array($filepath, $this->csspaths)) return;
		
		// remember this path for when we generate the template
		$this->csspaths[] = $filepath;
	}
	// tells the template to add a js file to the head tags
	// given a filepath relative to the docroot (i.e. 'js/scripts.js')
	public function js($filepath)
	{
		// don't add it if it's already in there
		if(in_array($filepath, $this->jspaths)) return;
		
		// remember this path for when we generate the template
		$this->jspaths[] = $filepath;
	}
	
	// returns a string of head tags that the template wants to render
	// echo this string in your <head> tag in your template if you want to use this feature
	public function headtags()
	{
		$str = '';
		foreach($this->csspaths as $p){
			$str .= '<link rel="stylesheet" href="'.f()->url->get($p).'">';
		}
		foreach($this->jspaths as $p){
			$str .= '<script src="'.f()->url->get($p).'"></script>';
		}

		// add a canonical tag if there is one:
		$canonical = f()->tag->canonical($this->canonicalPath);
		if(!empty($canonical)) $str .= $canonical;

		// also any extra head tags
		foreach($this->extra_head_tags as $ht){
			$str .= $ht;
		}

		return $str;
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

	public function setCanonicalPath($canonicalPath)
	{
		$this->canonicalPath = $canonicalPath;
	}

	public function add_head_tag($head_tag)
	{
		$this->extra_head_tags[] = $head_tag;
	}
}