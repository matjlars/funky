<ul class="feed">
	<?php foreach($modelobjs as $o){?>
		<li data-id="<?=$o->id?>">
			<a href="/<?=$url_path?>/edit/<?=$o->id?>">
				<?=$o->bridge_label()?>
			</a>
			<?php if($o->has_field('sort_id')){?>
				<a class="sort icon"></a>
			<?php }?>
			<a class="delete icon" title="Delete this <?=$modelname?>" onclick="feedpage.delete('<?=$o->id?>');return false;" href="#"></a>
		</li>
	<?php }?>
</ul>
