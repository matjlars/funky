<?php
$c = f()->config;
?>
<header>
	<h2>SMTP Config Editor</h2>
	<button class="save" form="edit-form">Save</button>
</header>

<p>This form is here to help guide you in setting up and editing SMTP config.</p>

<form action="" method="post" id="edit-form">
	<div class="row">
		<div class="field">
			<label for="smtp">Host</label>
			<input type="text" id="host" name="smtp_host" value="<?=$c->smtp_host?>">
		</div>
		<div class="field">
			<label for="port">Port</label>
			<input type="text" id="port" name="smtp_port" value="<?=$c->smtp_port?>">
		</div>
	</div>
	<div class="row">
		<div class="field">
			<label for="username">Username</label>
			<input type="text" id="username" name="smtp_username" value="<?=$c->smtp_username?>">
		</div>
		<div class="field">
			<label for="password">Password</label>
			<input type="password" id="password" name="smtp_password" value="<?=$c->smtp_password?>">
		</div>
	</div>
	<div class="field">
		<label for="from_email">From Email</label>
		<input type="text" id="from_email" name="smtp_from_email" value="<?=$c->smtp_from_email?>">
	</div>
	<div class="field">
		<label for="from_name">From Name</label>
		<input type="text" id="from_name" name="smtp_from_name" value="<?=$c->smtp_from_name?>">
	</div>
</form>