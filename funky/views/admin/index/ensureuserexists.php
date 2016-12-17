<h2>Create First User</h2>
<p>It is highly recommended you make this first user be the "adminadmin" so you can use that user to manage other users.</p>

<form method="post" action="/admin/index">
	<div class="field"><?=$user->field('email')->view()?></div>
	<div class="field"><?=$user->field('password')->view()?></div>
	<div class="field"><?=$user->field('roles')->view()?></div>
	<div class="field">
		<input type="submit" value="Set up first user"/>
	</div>
</form>