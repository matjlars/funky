<?php
namespace funky\services;

class format
{
	public function numeric($string)
	{
		$number = '';
		foreach(str_split($string) as $char)
		{
			if(is_numeric($char)) $number .= $char;
		}
		return $number;
	}
	
	// Formats a phone number for output:
	public function phone($phone)
	{
		$phone = $this->numeric($phone); // just in case it isn't already
		if(strlen($phone)==7)
		{
			return substr($phone,0,3).'-'.substr($phone,3);
		}
		if(strlen($phone)==10)
		{
			return '('.substr($phone,0,3).')'.substr($phone,3,3).'-'.substr($phone,6);
		}
		return $phone;
	}

	// sanitize it perfectly to be a slug (only containing [a-zA-Z0-9\-])
	public function slug($str)
	{
		$str = strtolower($str);
		$str = str_replace(' ', '-', $str);
		$str = preg_replace('/[^a-z0-9\-]/', '', $str);
		return $str;
	}

	// sanitize the filename to be sane
	public function filename($filename)
	{
		$filename = strtolower($filename);
		$filename = preg_replace('/[^a-z0-9\-\.]/', '-', $filename);
		$filename = preg_replace('/-+/', '-', $filename);
		return $filename;
	}
}