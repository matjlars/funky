<header>
	<h2>Editing <?=$modelname?></h2>
	<div>
		<a class="new button" href="/<?=$url_path?>/edit/0">New <?=$modelname?></a>
		<a class="back button" href="/<?=$url_path?>">Back to <?=$modelname_plural?></a>
		<button class="save" form="edit-form">Save</button>
	</div>
</header>

<form method="post" action="<?=f()->url->current()?>" id="edit-form">
	<?php foreach($fields as $field){?>
		<div class="field"><?=$modelobj->$field->view()?></div>
	<?php }?>
</form>
