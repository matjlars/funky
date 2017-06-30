<h2>Database Setup Required</h2>
<p>You must connect funky to your database. If you don't have a database, you must set one up. If your database already exists, skip to the form at the bottom of this page.</p>

<h3>Set up a database using PHPMyAdmin</h3>
<ol>
	<li>
		Open up PHPMyAdmin. How to do that depends on your server stack.</li>
		<ul>
			<li><strong>MAMP Link:</strong> <a href="http://localhost/MAMP/index.php?page=phpmyadmin" target="_blank">PHPMyAdmin for MAMP</a></li>
		</ul>
	<li>Create the database using the PHPMyAdmin interface.</li>
	<li>Paste the database name into the config helper.</li>
	<li>
		Create a database user. Paste the username and password into the config helper.</li>
		<ul>
			<li>You most likely want to set the "server" of the user to "localhost"</li>
			<li>You may as well make an extremely long and secure password because you will never have to type it in.</li>
		</ul>
	<li>Make sure the user is granted all priveleges for your database.</li>
</ol>

<h3>Config Helper</h3>
<form method="post" action="/admin/index">
	<div class="field">
		<label for="db_name">Database Name</label>
		<input type="text" name="db_name" id="db_name"<?=(isset(f()->config->db_name))?' value="'.f()->config->db_name.'"':''?>/>
	</div>
	<div class="field">
		<label for="db_user">Database User Name</label>
		<input type="text" name="db_user" id="db_user"<?=(isset(f()->config->db_user))?' value="'.f()->config->db_user.'"':''?>/>
	</div>
	<div class="field">
		<label for="db_password">Database User Password</label>
		<input type="password" name="db_password" id="db_password"<?=(isset(f()->config->db_password))?' value="'.f()->config->db_password.'"':''?>/>
	</div>
	<div class="field">
		<label></label>
		<input type="submit" value="Store my config values"/>
		<p>The only thing this will do is set these values in the config.txt file in your project root. Remember, don't commit that file to your git repository. Config is all environment-specific.</p>
	</div>
</form>