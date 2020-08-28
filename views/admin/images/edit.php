<h2>Editing Image</h2>

<form method="post" action="" enctype="multipart/form-data">
	<?php if(!empty($image->filename->get())){?>
		<div class="field">
			<label for="filename">Filename</label>
			<input type="text" readonly value="<?=$image->filename?>"/>
			<img src="<?=$image->url()?>" alt="Image Preview">
		</div>
	<?php }?>

	<div class="field">
		<label for="file"><?=(empty($image->filename->get())) ? 'Upload' : 'Replace'?> Image File</label>
		<input type="file" name="file">
	</div>

	<div class="field">
		<?=$image->alt->view()?>
		<em>Describe the image in detail. <a href="https://moz.com/learn/seo/alt-text" target="_blank">How to write good alt text</a></em>
	</div>

	<?php if($image->exists()){?>
		<div class="field">
			<label for="markdown_snippet">Markdown Snippet</label>
			<input type="text" id="markdown_snippet" value="[img.<?=$image->id?>]" readonly>
			<em>Copy this in to any markdown field to add this image.</em>
		</div>
	<?php }?>

	<a class="button" href="/admin/images">Back</a>
	<input type="submit" value="Save" class="green button"/>
</form>