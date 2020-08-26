<label for="<?=$field->name()?>"><?=$field->label()?></label>
<input type="text" name="<?=$field->name()?>" id="<?=$field->name()?>" value="<?=htmlentities($field->get())?>"<?=($field->get_slugify()) ? ' data-slugify="'.$field->get_slugify().'"' : ''?>>
