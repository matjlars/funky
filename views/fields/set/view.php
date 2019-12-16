<label><?=$field->label()?></label>

<?php //for if they're all unchecked?>
<input type="hidden" name="<?=$field->name()?>[]" value=""/>

<?php foreach($field->values() as $val){?>
<span class="checkbox">
	<input type="checkbox" name="<?=$field->name()?>[]" id="<?=$field->name()?>_<?=$val?>"<?=($field->in($val))?' checked':''?> value="<?=$val?>"/>
	<label for="<?=$field->name()?>_<?=$val?>"><?=$val?></label>
</span>
<?php }?>
