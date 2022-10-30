<label for="<?=$field->name()?>"><?=$field->label()?></label>
<select name="<?=$field->name()?>" id="<?=$field->name()?>">
	<?php if($field->isnullable()){?>
		<option value=""><?=$field->null_label()?></option>
	<?php }?>
	<?php foreach($field->values() as $val){?>
		<option value="<?=$val?>"<?=($field->dbval()==$val)?' selected':''?>><?=$field->option_label($val)?></option>
	<?php }?>
</select>