<?php
namespace funky\services;

// specifies a nice simple api for keeping track of environment-specific config settings
// you can use this service to read and write to the config
// it will store it in a file in the project root
// NOTE: the database uses this service to store database credentials, which means you should NOT commit the config file to your git repo
// ... also, that means you should not deploy the config file in your deploy process
class config
{
	private $data;
	private $filepath;
	
	public function __construct()
	{
		$this->filepath = f()->path->php('config.txt');
		$this->load();
	}
	// sets the config value and saves it to the config file
	public function __set($key,$value)
	{
		$this->data[$key] = $value;
		$this->save();
	}
	public function __get($key)
	{
		if(isset($this->data[$key])) return $this->data[$key];
		else throw new \exception('config value for key "'.$key.'" not found.');
	}
	public function __isset($key)
	{
		return isset($this->data[$key]);
	}
	// loads the file and fills $this->data with the data from the file
	private function load()
	{
		$this->data = array();
		
		// first see if the file exists
		if(!file_exists($this->filepath)) return;
		
		// open the file for reading
		$file = fopen($this->filepath, 'r');
		if($file === false) throw new \exception('config file "'.$this->filepath.'" exists, but is not readable. Make sure it is readable.');
		
		// read all the data from the file
		while(!feof($file)){
			$line = trim(fgets($file));
			if(!empty($line)){
				$tokens = explode(' ', $line, 2);
				if(!isset($tokens[0]) || !isset($tokens[1])){
					// they must not have entered a value, so default to empty string
					$this->data[$line] = '';
				}else{
					// we have both tokens, so set it properly
					$this->data[$tokens[0]] = $tokens[1];
				}
			}
		}
		fclose($file);
	}
	private function save()
	{
		// open the file for writing.
		// this also creates the file if it doesn't exist
		$file = fopen($this->filepath, 'w');
		if($file === false){
			throw new \exception('the config file "" failed to create/open for writing');
		}
		
		// pack all data into a string
		$str = '';
		foreach($this->data as $key=>$val){
			$str .= $key.' '.$val."\n";
		}
		
		// write the data to the file:
		$byteswritten = fwrite($file, $str);
		if($byteswritten === false){
			throw new \exception('failed to write data to the config file "'.$this->filepath.'". the file was opened successfully, but failed to write somehow.');
		}
	}
}