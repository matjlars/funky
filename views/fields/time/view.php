<label for="<?=$field->name()?>"><?=$field->label()?></label>
<input type="time" name="<?=$field->name()?>" id="<?=$field->name()?>" value="<?=$field->format($field->field_format())?>">