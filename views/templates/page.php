<!DOCTYPE html>
<html>
<head>
	<title>Web Site</title>
	<link rel="stylesheet" href="/css/styles.css"/>
	<script src="/js/scripts.js"></script>
	<?=f()->tag->canonical()?>
</head>
<body>
	<header>
		<h1>Funky</h1>
	</header>
	<main><?=$content?></main>
</body>
</html>