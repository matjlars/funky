<h2>Users Table</h2>
<p>The "users" table does not exist in the database, so there's no way to log in.</p>
<p>Press the button below to run the following SQL to create the users table in the database.</p>

<form action="/admin/index" method="post">
	<div class="field">
		<label>SQL that will run</label>
		<textarea disabled rows="5" cols="50"><?=$sql?></textarea>
	</div>
	<button name="createusers" class="save">Set up users table</button>
</form>