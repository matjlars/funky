<?php
$trace = $e->getTrace();
?>
<div style="background:#b88;padding:1rem;">
	<h1>Error</h1>
	<p><?=$e->getMessage()?></p>
	<h2>Exception Info</h2>
	<p>The exception was thrown in <?=$e->getfile()?> on line <?=$e->getLine()?></p>
	<h2>Trace</h2>
	<table>
		<thead>
			<tr>
				<th>File</th>
				<th>Line</th>
				<th>Class</th>
				<th>Function</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($trace as $t){?>
				<tr>
					<td><?=(empty($t['file']))?'':$t['file']?></td>
					<td><?=(empty($t['line']))?'':$t['line']?></td>
					<td><?=(empty($t['class']))?'':$t['class']?></td>
					<td><?=(empty($t['function']))?'':$t['function']?>()</td>
				</tr>
			<?php }?>
		</tbody>
	</table>
</div>
