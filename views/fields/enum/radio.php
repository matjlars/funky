<label><?=$field->label()?></label>

<?php if($field->isnullable()){?>
	<span class="checkbox">
		<input type="radio" name="<?=$field->name()?>" value="" id="<?=$field->name()?>_"<?=empty($field->dbval()) ? ' checked' : ''?>>
		<label for="<?=$field->name()?>_">N/A</label>
	</span>
<?php }?>

<?php foreach($field->values() as $val){
	$id = $field->name().'_'.$val;
	?>
	<span class="checkbox">
		<input type="radio" name="<?=$field->name()?>" value="<?=$val?>" id="<?=$id?>"<?=($field->dbval()==$val) ? ' checked' : ''?>>
		<label for="<?=$id?>"><?=$field->option_label($val)?></label>
	</span>
<?php }?>
