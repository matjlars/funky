<style type="text/css">
#admin-login{
	text-align:center;
}
#admin-login form{
	display:inline-block;
	padding:1em;
	margin:1em;
	background-color:#eee;
}
</style>

<? if(!empty($error)){?>
	<p class="error"><?=$error?></p>
<? }?>

<div id="admin-login">
	<h2>Log In</h2>
	<form action="<?=j()->path->current_url()?>" method="post" id="adminloginform">
		<input type="text" id="username" name="username" placeholder="Username.."/>
		<input type="password" name="password" placeholder="Password.."/>
		<input type="submit" name="login" value="Log In"/>
	</form>
</div>

<script type="text/javascript">
document.getElementById('username').focus();
</script>