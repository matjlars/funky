<?php

// get all records that are currently selected
$records = $field->get();

// gather the IDs to put in the input[type=hidden]
$selected_ids = [];
foreach($records as $record){
	$selected_ids[] = $record->id;
}
$selected_ids = implode(',', $selected_ids);

?>
<div class="bridge_field" data-ajax_path="<?=$field->ajax_path()?>">
	<label for="<?=$field->name()?>"><?=$field->label()?></label>
	<input type="hidden" name="<?=$field->name()?>" value="">
	<input type="search" id="<?=$field->name()?>" placeholder="Search">
	<ul class="search-results"></ul>
	<ul class="tags"></ul>
</div>
<?php foreach($records as $record){?>
	<script>bridge_field.select($('#<?=$field->name()?>').closest('.bridge_field'), '<?=$record->id?>', "<?=htmlspecialchars($record->bridge_label())?>");</script>
<?php }?>