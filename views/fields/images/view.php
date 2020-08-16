<div class="imagesfield">
	<label><?=$field->label()?></label>
	<input type="hidden" name="<?=$field->name()?>" value="<?=$field->dbval()?>">

	<div class="thumbnails">
		<?=f()->view->load('admin/images/imagesfield_thumbnails', [
			'images'=>$field->get(),
		])?>
	</div>

	<a class="button" onclick="imagesfield.open_modal(this);">Upload New Image</a>
</div>