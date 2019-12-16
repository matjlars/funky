<h2>Query Result</h2>

<?php if(empty($result)){?>
	<p>No result.</p>
<?php }else{?>
	<?php if(is_bool($result)){?>
		<?php if($result){?>
			<p>Statement executed successfullly.</p>
		<?php }else{?>
			<p>$result was false which doesn't make sense because it should've been an exception error</p>
		<?php }?>
	<?php }else{?>
		<table>
			<thead>
				<tr>
					<?php foreach($result->row() as $key=>$val){?>
						<th><?=$key?></th>
					<?php }?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $row){?>
					<tr>
						<?php foreach($row as $key=>$val){?>
							<td><?=$val?></td>
						<?php }?>
					</tr>
				<?php }?>
			</tbody>
		</table>
	<?php }?>
<?php }?>
