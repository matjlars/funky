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
}
