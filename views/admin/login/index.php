<style type="text/css">
#admin-login{
	text-align:left;
	display:inline-block;
	padding:0 1rem;
}
</style>

<header>
	<h2>Log In</h2>
</header>

<?php if(!empty($error)){?>
	<p class="error"><?=$error?></p>
<?php }?>

<form action="<?=f()->url->current()?>" method="post" id="admin-login">
	<div class="field">
		<label for="email">Email Address</label>
		<input type="text" id="email" name="email" autofocus required>
	</div>
	<div class="field">
		<label for="password">Password</label>
		<input type="password" name="password" required>
	</div>
	<button>Log In</button>
</form>