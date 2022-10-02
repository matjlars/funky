<?php if($images->count() == 0){?>
	<p>There are no images yet.</p>
<?php }else{?>
	<ul class="feed">
		<?php foreach($images as $i){?>
			<li data-id="<?=$i->id?>">
				<a href="/admin/images/edit/<?=$i->id?>">
					<?=$i->name?>
				</a>
				<a class="delete icon" title="Delete this image" onclick="feedpage.delete('<?=$i->id?>');return false;" href="#"></a>
			</li>
		<?php }?>
	</ul>
<?php }?>