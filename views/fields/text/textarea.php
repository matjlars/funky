<label for="<?=$field->name()?>"><?=$field->label()?></label>
<textarea name="<?=$field->name()?>" id="<?=$field->name()?>" rows="<?=isset($rows) ? $rows : '5' ?>" cols="50"><?=htmlentities($field->get())?></textarea>