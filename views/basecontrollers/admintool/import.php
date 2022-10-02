<header>
	<h2>Import</h2>
	<div>
		<a href="/<?=$path?>" class="back button">Back</a>
		<a href="/<?=$path?>/import/?download_template=1" class="export button">Download Template</a>
	</div>
</header>

<h3>Column Headers</h3>
<p>These are all the possible column headers you can put in the first row of the spreadsheet. They must exactly match these in order to import correctly.</p>
<ul>
	<?php foreach($headers as $h){?>
		<li><?=$h?></li>
	<?php }?>
</ul>

<h3>Upload CSV File</h3>
<form action="/<?=$path?>/import" method="post" enctype="multipart/form-data">
	<div class="field">
		<label for="file">CSV File</label>
		<input type="file" id="file" name="file" accept=".csv" required>
	</div>
	<p>A new <?=$modelname?> will be created for every row in the CSV file.</p>
	<input type="submit" value="Upload">
</form>