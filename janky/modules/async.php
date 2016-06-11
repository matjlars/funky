<?php
class async extends j_module
{
	public function getsource($url)
	{
		$c = curl_init();
		curl_setopt($c,CURLOPT_URL,$url);
		curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
		$response = curl_exec($c);
		curl_close($c);
		return $response;
	}
}