<label for="<?=$field->name()?>"><?=$field->label()?></label>
<input type="text" name="<?=$field->name()?>" id="<?=$field->name()?>" value="<?=htmlentities($field->get())?>"/>
