<?php foreach($images as $img){?>
	<img src="<?=$img->url()?>" alt="<?=$img->alt?>" width="100" onclick="imagesfield.open_modal(this, '<?=$img->id?>');">
<?php }?>
