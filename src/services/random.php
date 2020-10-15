<?php
namespace funky\services;

class random
{
	public function string($length=32, $characters='')
	{
		if(empty($characters)){
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$charcount = strlen($characters);
			$str = '';
			for($i = 0; $i < $length; $i++){
				$str .= $characters[rand(0, $charcount - 1)];
			}
			return $str;
		}
	}

	// generates a random string of the given length.
	// this one uses characters that aren't confusing for passwords, plus some symbols.
	public function password($length=16){
		// characters minus confusing ones like 0, o, O, i, I, and l
		$chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789#$%&!';
		return $this->string($length, $chars);
	}
}
