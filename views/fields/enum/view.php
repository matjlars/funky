<label for="<?=$field->name()?>"><?=$field->label()?></label>
<select name="<?=$field->name()?>" id="<?=$field->name()?>">
	<option value="">&mdash; Choose One &mdash;</option>
	<?foreach($field->values() as $val){?>
		<option value="<?=$val?>"<?=($field->dbval()==$val)?' selected':''?>><?=$val?></option>
	<?}?>
</select>