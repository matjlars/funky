<?php
namespace funky\services;

// use this service to generate lorem ipsum text for a styleguide or mockup
class lorem
{
	private $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ';
	private $textlength;
	
	public function __construct()
	{
		$this->textlength = strlen($this->text);
	}
	
	public function get($length)
	{
		$text = '';
		$l = $length;
		// handle lengths longer than the text length
		while($l > $this->textlength){
			$text .= $this->text;
			$l -= $this->textlength;
		}
		if($l > 0){
			$text .= substr($this->text, 0, $l);
		}
		return $text;
	}
	public function random($minlength, $maxlength)
	{
		$length = rand($minlength, $maxlength);
		return $this->get($length);
	}
}