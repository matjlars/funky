<style type="text/css">
#admin-login{
	text-align:center;
}
#admin-login form{
	text-align:left;
	display:inline-block;
	padding:1em;
	margin:1em;
}
</style>

<? if(!empty($error)){?>
	<p class="error"><?=$error?></p>
<? }?>

<div id="admin-login">
	<h2>Log In</h2>
	<form action="<?=f()->path->current_url()?>" method="post" id="adminloginform">
		<div class="field">
			<label for="email">Email Address</label>
			<input type="text" id="email" name="email"/>
		</div>
		<div class="field">
			<label for="password">Password</label>
			<input type="password" name="password"/>
		</div>
		<div class="field">
			<input type="submit" name="login" value="Log In"/>
		</div>
	</form>
</div>

<script type="text/javascript">
document.getElementById('email').focus();
</script>