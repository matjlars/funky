<label for="<?=$field->name()?>"><?=$field->label()?></label>
<select id="<?=$field->name()?>" name="<?=$field->name()?>">
	<option value="0">&mdash; Choose One &mdash;</option>
	<?php foreach($field->options() as $key=>$val){?>
		<option value="<?=$key?>"<?=($field->dbval()==$key)?' selected':''?>><?=$val?></option>
	<?php }?>
</select>