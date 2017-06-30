<?if(empty($migrations)){?>
	<h3>Migrations are up to date! <a class="button" onclick="getmigrations();">refresh</a></h3>
<?}else{?>
	<h3>Migrations</h3>
	<table>
		<colgroup>
			<col style="width:12rem;"/>
			<col/>
			<col style="width:10rem;"/>
		</colgroup>
		<thead>
			<tr>
				<th>Migration Name</th>
				<th>SQL</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?foreach($migrations as $migration){?>
				<tr>
					<td><?=$migration['name']?></td>
					<td class="has-sql"><?=$migration['sql']?></td>
					<td>
						<a class="button" onclick="runmigration(this);">Run</a>
					</td>
				</tr>
			<?}?>
		</tbody>
	</table>
<?}?>
