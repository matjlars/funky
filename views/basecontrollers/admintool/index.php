<section>
	<header>
		<h2><?=$modelclass::label_plural()?></h2>
		<a class="button" href="<?=f()->url->get($url_path.'/edit/0')?>">New <?=$modelclass::label_singular()?></a>
	</header>
	<div id="results"></div>
</section>

<script>
feedpage.init('/<?=$url_path?>', '<?=$modelclass::label_singular()?>');
</script>
