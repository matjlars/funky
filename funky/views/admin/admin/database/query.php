<h2>Query Result</h2>

<?if(empty($result)){?>
	<p>No result.</p>
<?}else{?>
	<?if(is_bool($result)){?>
		<?if($result){?>
			<p>Statement executed successfullly.</p>
		<?}else{?>
			<p>$result was false which doesn't make sense because it should've been an exception error</p>
		<?}?>
	<?}else{?>
		<table>
			<thead>
				<tr>
					<?foreach($result->row() as $key=>$val){?>
						<th><?=$key?></th>
					<?}?>
				</tr>
			</thead>
			<tbody>
				<?foreach($result as $row){?>
					<tr>
						<?foreach($row as $key=>$val){?>
							<td><?=$val?></td>
						<?}?>
					</tr>
				<?}?>
			</tbody>
		</table>
	<?}?>
<?}?>
