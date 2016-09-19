<?php
class underconstruction extends j_service
{
	public function restrict()
	{
		// If we're logged in, yay!
		if($this->isloggedin()) return;
		
		// If we're trying to log in, go for it:
		if(!empty($_POST['underconstructionlogin']))
		{
			if($_POST['password'] == j()->config->underconstructionpassword)
			{
				j()->session->underconstruction_loggedin = 1;
				return;
			}
		}
		
		// If we're not logged in, spit out a login form and die:
		?>
		<h2>This site is under construction!</h2>
		<p>Please enter the password to be able to enter the site for development.</p>
		<form action="" method="post">
			<input type="hidden" name="underconstructionlogin" value="1"/>
			<input type="password" name="password" placeholder="Password.."/>
			<input type="submit" name="submit" value="Log In"/>
		</form>
		<?
		exit(0);
	}
	
	public function isloggedin()
	{
		if(empty(j()->session->underconstruction_loggedin)) return false;
		return true;
	}
}