<?if($images->count() == 0){?>
	<p>There are no images yet.</p>
<?}else{?>
	<ul class="feed">
		<?foreach($images as $i){?>
			<li data-id="<?=$i->id?>">
				<a href="/admin/images/edit/<?=$i->id?>">
					<?=$i->alt?>
				</a>
			</li>
		<?}?>
	</ul>
<?}?>