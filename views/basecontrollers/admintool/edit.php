<header>
	<h2><a href="/<?=$url_path?>"><?=$model_label_plural?></a> / <?=($modelobj->exists()) ? 'Edit' : 'New'?></h2>
	<a class="new button" href="/<?=$url_path?>/edit/0">New <?=$model_label?></a>
	<button class="save" form="edit-form">Save</button>
</header>

<form method="post" action="" enctype="multipart/form-data" id="edit-form">
	<?php foreach($fields as $field){?>
		<div class="field"><?=$modelobj->$field->view()?></div>
	<?php }?>
</form>
