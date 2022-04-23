<section>
	<header>
		<h2><?=ucwords($modelname_plural)?></h2>
		<a class="button" href="<?=f()->url->get($url_path.'/edit/0')?>">New <?=$modelname?></a>
	</header>
	<div id="results"></div>
</section>

<script>
feedpage.init('/<?=$url_path?>', '<?=$modelname?>');
</script>
