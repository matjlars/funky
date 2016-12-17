<p>An error was encountered that prevented even attempting to log in: <?=$message?></p>

<h2>Database Setup Required</h2>
<p>You must set up your database. Here's are instructions for using PHPMyAdmin or the mysql cli.</p>


<h3>Using PHPMyAdmin</h3>
<ol>
	<li>Open up PHPMyAdmin. How to do that depends on your server stack.</li>
	<li>Create the database using the PHPMyAdmin interface.</li>
	<li>Paste the database name into the config helper.</li>
	<li>Create a database user. Paste the username and password into the config helper.</li>
	<li>Make sure the user is granted all priveleges for your database. You probably don't need GRANT, LOCK TABLES, or REFERENCES.</li>
</ol>


<h3>Using mysql command line interface (cli)</h3>


<h3>Config Helper</h3>
<form method="post" action="/admin/index">
	<div class="field">
		<label for="name">Database Name</label>
		<input type="text" name="name" id="name"/>
	</div>
	<div class="field">
		<label for="username">Database User Name</label>
		<input type="text" name="username" id="username"/>
	</div>
	<div class="field">
		<label for="password">Database User Password</label>
		<input type="text" name="password" id="password"/>
	</div>
	<div class="field">
		<label></label>
		<input type="submit" value="Store my config values"/>
		<p>The only thing this will do is set these values in the config.php file in your project root. Remember, don't commit that file to your git repository. Config is all environment-specific.</p>
	</div>
</form>