<label for="<?=$field->name()?>"><?=$field->label()?></label>
<select id="<?=$field->name()?>" name="<?=$field->name()?>">
	<option value="">&mdash; Choose One &mdash;</option>
	<?foreach($field->options() as $key=>$val){?>
		<option value="<?=$key?>"<?=($field->dbval()==$key)?' selected':''?>><?=$val?></option>
	<?}?>
</select>