<header>
	<h2>Editing <?=$modelname?></h2>
	<div>
		<a class="button" href="/<?=$url_path?>/edit/0">New <?=$modelname?></a>
		<a class="button" href="/<?=$url_path?>">Back to <?=$modelname_plural?></a>
		<input type="submit" value="Save" class="green" form="edit-form">
	</div>
</header>

<form method="post" action="<?=f()->url->current()?>" id="edit-form">
	<?php foreach($fields as $field){?>
		<div class="field"><?=$modelobj->$field->view()?></div>
	<?php }?>
</form>
