<header>
	<h2>Editing Image</h2>
	<div>
		<a class="new button" href="/admin/images/new">New Image</a>
		<a class="back button" href="/admin/images">Back to Images</a>
		<button class="save" form="edit-form">Save</button>
	</div>
</header>

<form method="post" action="" enctype="multipart/form-data" id="edit-form">
	<div class="row">
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
	</div>

	<div class="field">
		<?=$image->name->view()?>
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
</form>