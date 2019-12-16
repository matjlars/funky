<label for="<?=$field->name()?>"><?=$field->label()?></label>
<select id="<?=$field->name()?>" name="<?=$field->name()?>">
	<option value="">&mdash; Choose One &mdash;</option>
	<?php foreach(\funky\fields\base\state::ALL as $key=>$name){?>
		<option value="<?=$key?>"<?=($key==$field->dbval())?' selected':''?>><?=$name?></option>
	<?php }?>
</select>
