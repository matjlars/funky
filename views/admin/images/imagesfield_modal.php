<header>
	<h2>Editing Image</h2>
</header>

<section class="content">
	<form>
		<?php if($image->exists()){?>
			<?=$image->tag()?>
		<?php }?>

		<div class="field">
			<label for="imagesfield_file">Image File</label>
			<input type="file" id="imagesfield_file" name="files[]" multiple>
		</div>

		<div class="field">
			<label for="imagesfield_alt">Alt Text</label>
			<input type="text" id="imagefield_alt" name="alt"<?=(empty($image->alt->get())) ? '' : ' value="'.$image->alt.'"'?>>
		</div>
	</form>
</section>

<footer>
	<button type="button" onclick="modal.close();">Cancel</button>
	<?php if($image->exists()){?>
		<button type="button" class="delete" onclick="imagesfield.delete('<?=$image->id?>');">Delete</button>
	<?php }?>
	<button type="button" class="save" onclick="imagesfield.save_modal('<?=($image->exists()) ? $image->id : 0?>');">Save</button>
</footer>
