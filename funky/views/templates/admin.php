<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<link rel="stylesheet" href="/css/admin/admin.css"/>
	<link rel="stylesheet" href="/css/admin/tabs.css"/>
	<link rel="stylesheet" href="/css/admin/flash.css"/>
	<link rel="stylesheet" href="/css/admin/feed.css"/>
	<link rel="stylesheet" href="/css/admin/imagefield.css"/>
	<link rel="stylesheet" href="/css/admin/modal.css"/>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/admin/admin.js"></script>
	<script src="/js/admin/tabs.js"></script>
	<script src="/js/admin/flash.js"></script>
	<script src="/js/admin/imagefield.js"></script>
	<script src="/js/admin/modal.js"></script>
</head>
<body>
	<h1><?=f()->lang->admin_title?></h1>
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
