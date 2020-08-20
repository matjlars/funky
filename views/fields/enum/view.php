<label for="<?=$field->name()?>"><?=$field->label()?></label>
<select name="<?=$field->name()?>" id="<?=$field->name()?>">
	<?php if($field->isnullable()){?>
		<option value="">&mdash; Choose One &mdash;</option>
	<?php }?>
	<?php foreach($field->values() as $val){?>
		<option value="<?=$val?>"<?=($field->dbval()==$val)?' selected':''?>><?=$val?></option>
	<?php }?>
</select>