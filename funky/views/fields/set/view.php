<?foreach($field->values() as $val){?>
<label for="<?=$field->name()?>_<?=$val?>"><?=$val?></label>
<input type="checkbox" name="<?=$field->name()?>[<?=$val?>]" id="<?=$field->name()?>_<?=$val?>"<?=($field->in($val))?' checked':''?>/>
<?}?>