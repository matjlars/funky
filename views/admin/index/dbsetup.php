<header>
	<h2>Database Setup</h2>
	<button class="save" form="edit-form">Save</button>
</header>

<p>You must connect funky to your database. If you don't have a database, you must create one first.</p>
<p>The only thing this will do is set these values in the config.txt file in your project root. Remember, don't commit that file to your git repository. Config is all environment-specific.</p>

<h3>Config Helper</h3>
<form method="post" action="/admin/index" id="edit-form">
	<div class="field">
		<label for="db_name">Database Name</label>
		<input type="text" name="db_name" id="db_name" value="<?=f()->config->db_name?>">
	</div>
	<div class="field">
		<label for="db_user">Database User Name</label>
		<input type="text" name="db_user" id="db_user" value="<?=f()->config->db_user?>">
	</div>
	<div class="field">
		<label for="db_password">Database User Password</label>
		<input type="password" name="db_password" id="db_password" value="<?=f()->config->db_password?>">
	</div>
</form>