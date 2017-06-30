<label for="<?=$field->name()?>"><?=$field->label()?></label>
<input type="file" name="<?=$field->name()?>" id="<?=$field->name()?>"/>
<?if(!empty($field->url())){?>
<a href="<?=$field->url()?>">Download File</a>
<?}?>