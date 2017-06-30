<?php
namespace funky\services;

class lang
{
	protected $langcode;
	protected $text = array();
	
	public function __construct()
	{
		// figure out which language the user prefers
		$this->autodetect();
	}
	// gets some text in the current language based on the text key
	public function __get($key)
	{
		if(!array_key_exists($key, $this->text)) throw new \exception('language "'.$this->langcode.'" does not have key "'.$key.'". check file "'.$this->filename().'"');
		return $this->text[$key];
	}
	// change the current language. this loads all text for that language
	public function setlangcode($langcode)
	{
		$this->langcode = $langcode;

		// read from the text file:
		$f = fopen($this->filename(), 'r');
		// read each line of the file
		while(($line = fgets($f, 4096)) !== false){
			$delimpos = strpos($line, ':');
			$key = substr($line, 0, $delimpos);
			$text = substr($line, $delimpos+1);
			$this->text[$key] = $text;
		}
		// make sure the file reading terminated properly
		if(!feof($f)) throw new \exception('unexpected end to the language file "'.$this->filename().'"');
		fclose($f);
	}
	protected function filename()
	{
		return f()->path->php('lang/'.$this->langcode.'.txt');
	}
	// attempts to auto-detect the language based on globals
	protected function autodetect()
	{
		// try to get it from the session
		if(isset(f()->session->langcode)){
			$this->setlangcode(f()->session->langcode);
			return;
		}
		
		// try to get it from the cookies
		if(isset($_COOKIE['langcode'])){
			$this->setlangcode($_COOKIE['langcode']);
			return;
		}
		
		// try to get it from the http header
		/* TODO make this work.
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			$this->setlangcode($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			return;
		}
		*/
		
		// try to get it from the site config
		if(isset(f()->config->langcode)){
			$this->setlangcode(f()->config->langcode);
			return;
		}

		// nothing else worked, so default to english.
		// a lot of people speak english, right?
		$this->setlangcode('en');
	}
}