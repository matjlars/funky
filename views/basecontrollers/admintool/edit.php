<header>
	<h2><?=$modelobj->exists() ? 'Edi' : 'Crea'?>ting <?=$modelclass::label_singular()?></h2>
	<div>
		<a class="button" href="/<?=$url_path?>/edit/0">New <?=$modelclass::label_singular()?></a>
		<a class="button" href="/<?=$url_path?>">Back to <?=$modelclass::label_plural()?></a>
		<input type="submit" value="Save" class="green" form="edit-form">
	</div>
</header>

<form method="post" action="<?=f()->url->current()?>" id="edit-form">
	<?php foreach($fields as $field){?>
		<div class="field"><?=$modelobj->$field->view()?></div>
	<?php }?>
</form>
