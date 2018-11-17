<label for="<?=$field->name()?>"><?=$field->label()?></label>
<textarea name="<?=$field->name()?>" id="<?=$field->name()?>" rows="10" cols="50"><?=$field->get()?></textarea>
<em>This content area uses <a href="#" onclick="markdown.help();return false;">Markdown</a>.</em>
