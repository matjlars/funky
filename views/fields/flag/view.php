<input type="hidden" name="<?=$field->name()?>" value="0">
<input type="checkbox" name="<?=$field->name()?>" id="<?=$field->name()?>"<?=($field->get()) ? ' checked' : ''?> value="1">
<label for="<?=$field->name()?>"><?=$field->label()?></label>
