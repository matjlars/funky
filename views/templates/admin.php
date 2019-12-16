<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<link rel="stylesheet" href="/css/admin.css"/>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/admin.js"></script>
</head>
<body>
	<h1>Admin</h1>
	<?=f()->view->load('admin/nav')?>
	<?php if(isset($premainview)){?>
		<?=f()->view->load($premainview)?>
	<?php }?>
	<main><?=$content?></main>
<?php
$flash = f()->flash->pop();
foreach($flash as $type=>$messages){
	foreach($messages as $message){
		?><script>flash.show('<?=$type?>','<?=$message?>');</script><?php
	}
}
?>
</body>
</html>
