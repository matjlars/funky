<label for="<?=$field->name()?>"><?=$field->label()?></label>
<input type="datetime-local" name="<?=$field->name()?>" id="<?=$field->name()?>" value="<?=$field->is_null() ? '' : $field->format('Y-m-d\TH:i')?>">