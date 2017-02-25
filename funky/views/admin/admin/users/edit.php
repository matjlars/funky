<h2>Edit User</h2>
<form action="<?=f()->url->current()?>" method="post">
	<div class="field">
		<label for="user_email">Email Address</label>
		<input type="text" name="user[email]" id="user_email" value="<?=$user->email?>"/>
	</div>
	<div class="field">
		<label for="user_password"><?=$user->exists()?'Reset':'Set'?> Password</label>
		<input type="password" name="user[password]" id="user_password"/>
	</div>
	<h3>Roles</h3>
	<?foreach($user->roles->values() as $role){?>
		<div class="field">
			<input type="checkbox" name="user[roles][]" id="user_role_<?=$role?>" value="<?=$role?>"<?=($user->hasrole($role))?' checked':''?>/>
			<label for="user_role_<?=$role?>"><?=$role?></label>
		</div>
	<?}?>
	<div class="field">
		<a class="button" href="/admin/admin/users">Cancel</a>
		<input class="green" type="submit" value="Save"/>
		<?if($user->exists()){?>
			<a class="red button" href="/admin/admin/users/delete/<?=$user->id?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
		<?}?>
	</div>
</form>