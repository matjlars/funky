<header>
	<h2><?=$model_label_plural?></h2>
	<a class="new button" href="/<?=$url_path?>/edit/0">New <?=$model_label?></a>
</header>
<div id="results"></div>

<script>
feedpage.init('/<?=$url_path?>', '<?=$model_label?>');
</script>
