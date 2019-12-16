<h2>Edit User</h2>

<form action="<?=f()->url->current()?>" method="post">
	<div class="field">
		<label for="email">Email Address</label>
		<input type="text" name="email" id="email" value="<?=$user->email?>"/>
	</div>
	<div class="field">
		<label for="password"><?=$user->exists()?'Reset':'Set'?> Password</label>
		<input type="password" name="password" id="password"/>
	</div>
	<h3>Roles</h3>
	<?php foreach($user->roles->values() as $role){?>
		<div class="field">
			<input type="checkbox" name="roles[]" id="role_<?=$role?>" value="<?=$role?>"<?=($user->hasrole($role))?' checked':''?>/>
			<label for="role_<?=$role?>"><?=$role?></label>
		</div>
	<?php }?>
	<div class="field">
		<a class="button" href="/admin/admin/users">Cancel</a>
		<input class="green" type="submit" value="Save"/>
		<?php if($user->exists()){?>
			<a class="red button" href="/admin/admin/users/delete/<?=$user->id?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
		<?php }?>
	</div>
</form>

<?php foreach($user->errors() as $error){?>
<script>flash.error('<?=$error?>');</script>
<?php }?>