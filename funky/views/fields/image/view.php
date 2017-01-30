<?php
$image = $field->get();
?>
<div class="imagefield">
	<label for="<?=$field->name()?>"><?=$field->label()?></label>
	<img src="<?=($image->exists())?$image->url():''?>" alt="Upload Image"/>
	<input type="file" class="hidden"/>
	<input type="hidden" name="<?=$field->name()?>" value="<?=$field->dbval()?>"/>
</div>