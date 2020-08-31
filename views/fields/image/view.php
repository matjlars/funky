<?php
$image = $field->get();
?>
<div class="imagefield">
	<label for="<?=$field->name()?>"><?=$field->label()?></label>
	<img src="<?=($image->exists())?$image->url():''?>" alt="Upload Image">
	<input type="file" class="hidden">
	<input type="hidden" name="<?=$field->name()?>[image_id]" value="<?=$field->dbval()?>">
	<label for="<?=$field->name()?>-alt"><?=$field->label()?> Alt</label>
	<input type="text" name="<?=$field->name()?>[alt]" id="<?=$field->name()?>-alt" value="<?=$image->alt?>">
</div>