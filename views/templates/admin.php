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
	<script src="/js/admin/modal.js"></script>
	<script src="/js/admin/imagefield.js"></script>
</head>
<body>
	<h1>Admin</h1>
	<?=f()->view->load('admin/nav')?>
	<?if(isset($premainview)){?>
		<?=f()->view->load($premainview)?>
	<?}?>
	<main><?=$content?></main>
</body>
</html>
