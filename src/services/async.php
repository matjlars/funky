<?php
namespace funky\services;

class async{
	public function getsource($url){
		$c = curl_init();
		curl_setopt($c,CURLOPT_URL,$url);
		curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
		$response = curl_exec($c);
		curl_close($c);
		return $response;
	}
}