<h2>Editing Image</h2>

<form method="post" action="<?=f()->url->current()?>">
	<div class="field">
		<label for="filename">Filename</label>
		<input type="text" readonly value="<?=$image->filename?>"/>
	</div>
	<div class="field"><?=$image->alt->view()?></div>

	<a class="button" href="/admin/images">Back</a>
	<input type="submit" value="Save" class="green button"/>
</form>