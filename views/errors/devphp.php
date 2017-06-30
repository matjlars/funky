<style>
.error{
	border:1px solid red;
	font-size:1.2rem;
	background:rgba(255,0,0,0.1);
	padding:1rem;
}
table{
	border-collapse:collapse;
}
td,th{
	border:1px solid black;
}
</style>

<div class="error">
	<h2><?=f()->debug->errstr($level)?></small> Error</h2>
	<p><?=$message?></p>
	<p>File: <?=$file?></p>
	<p>Line: <?=$line?></p>
	<table>
		<caption>Context</caption>
		<?foreach($context as $key=>$val){?>
			<tr>
				<th><?=$key?></th>
				<td>
					<?if(is_string($val)){
						echo $val;
					}else{
						var_dump($val);
					}?>
				</td>
			</tr>
		<?}?>
	</table>
	<table>
		<caption>Session</caption>
		<?foreach($_SESSION as $key=>$val){?>
			<tr>
				<th><?=$key?></th>
				<td>
					<?if(is_string($val)){
						echo $val;
					}else{
						var_dump($val);
					}?>
				</td>
			</tr>
		<?}?>
	</table>
	<table>
		<caption>Get Parameters</caption>
		<?foreach($_GET as $key=>$val){?>
			<tr>
				<th><?=$key?></th>
				<td>
					<?if(is_string($val)){
						echo $val;
					}else{
						var_dump($val);
					}?>
				</td>
			</tr>
		<?}?>
	</table>
	<table>
		<caption>Post Parameters</caption>
		<?foreach($_POST as $key=>$val){?>
			<tr>
				<th><?=$key?></th>
				<td>
					<?if(is_string($val)){
						echo $val;
					}else{
						var_dump($val);
					}?>
				</td>
			</tr>
		<?}?>
	</table>
	<table>
		<caption>Server Variables</caption>
		<?foreach($_SERVER as $key=>$val){?>
			<tr>
				<th><?=$key?></th>
				<td>
					<?if(is_string($val)){
						echo $val;
					}else{
						var_dump($val);
					}?>
				</td>
			</tr>
		<?}?>
	</table>
	<table>
		<caption>Cookies</caption>
		<?foreach($_COOKIE as $key=>$val){?>
			<tr>
				<th><?=$key?></th>
				<td>
					<?if(is_string($val)){
						echo $val;
					}else{
						var_dump($val);
					}?>
				</td>
			</tr>
		<?}?>
	</table>
</div>