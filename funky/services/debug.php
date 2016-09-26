<?php
class debug
{
	public function dump($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}
	public function error($error='')
	{
		if(empty($error)) $error = 'An Error Occurred.';
		
		// print the error:
		?>
		<div style="border:1px solid red;background-color:rgba(255,0,0,0.3);">
			<h2 style="color:red;">PHP Error</h2>
			<p><?=$error?></p>
		</div>
		<?
		
		// error_log the error:
		error_log($error);
	}
}