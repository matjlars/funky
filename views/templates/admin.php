<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/css/admin.css">
	<script src="/js/jquery.min.js"></script>
	<script src="/js/admin.js"></script>
</head>
<body>
	<header>
		<h1>Admin</h1>
		<div class="hamburger">
			<hr><hr><hr>
		</div>
	</header>
	<nav><?php
		if(f()->access->isloggedin()){
			foreach(f()->admin->nav_links() as $path=>$name){
				?><a href="<?=$path?>"<?=(f()->url->iscurrent($path))?' class="active"':''?>><?=$name?></a><?php
			}
			?>
			<a href="/admin/logout">Logout</a>
		<?php }?>
	</nav>
	<main><?=$content?></main>
<?php
$flash = f()->flash->pop();
foreach($flash as $type=>$messages){
	foreach($messages as $message){
		?><script>flash.show('<?=$type?>','<?=$message?>');</script><?php
	}
}
?>
	<div class="overlay"></div>
</body>
</html>
