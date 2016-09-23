<h2>Admin Tools</h2>

<table>
	<colgroup>
		<col/>
		<col/>
		<col/>
	</colgroup>
	<thead>
		<tr>
			<th>Tool</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($tools as $tool){?>
			<tr>
				<td><?=$tool['name']?></td>
				<td><?=$tool['status']?></td>
				<td>TODO</td>
			</tr>
		<? }?>
	</tbody>
</table>