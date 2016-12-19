<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<link rel="stylesheet" href="/css/admin.css"/>
	<script src="/js/jquery.min.js"></script>
</head>
<body>
	<h1>Site Administration</h1>
	<nav>
		<? if(f()->access->isloggedin()){?>
			<?foreach(array(
				'/admin/something'=>'Something',
			) as $path=>$name){?>
				<a href="<?=$path?>"<?=(f()->path->iscurrent($path))?' class="active"':''?>><?=$name?></a>
			<?}?>
			<aside>
				<?if(f()->access->hasrole('adminadmin')){?>
					<a href="/admin/admin">AdminAdmin</a>
				<?}?>
				<a href="/admin/logout">Logout</a>
			</aside>
		<?}?>
	</nav>
	<?if(isset($premainview)) f()->load->view($premainview)?>
	<main><?=$content?></main>
</body>
</html>
