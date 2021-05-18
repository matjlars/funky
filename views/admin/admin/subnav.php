<?php
$links = [
	'admin/admin/users'=>'Users',
	'admin/admin/config'=>'Config',
	'admin/admin/smtp'=>'SMTP',
	'admin/admin/database'=>'Database',
];
?>

<nav>
<?php foreach($links as $path => $label){?>
	<a href="<?=f()->url->get($path)?>"<?=(f()->url->iscurrent($path)) ? ' class="active"':''?>><?=$label?></a>
<?php }?>
</nav>
