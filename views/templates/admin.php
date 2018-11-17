<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<link rel="stylesheet" href="/css/admin/admin.css"/>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/admin/admin.js"></script>
</head>
<body>
	<h1>Admin</h1>
	<?=f()->view->load('admin/nav')?>
	<?if(isset($premainview)){?>
		<?=f()->view->load($premainview)?>
	<?}?>
	<main><?=$content?></main>
<?php
$flash = f()->flash->pop();
foreach($flash as $type=>$messages){
	foreach($messages as $message){
		?><script>flash.show('<?=$type?>','<?=$message?>');</script><?
	}
}
?>
</body>
</html>
