<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach($urls as $url){?>
	<url>
		<?php
		// if it's just a string, display that as the URL
		if(is_string($url)){
			?><loc><?=$url?></loc><?php
		}else{
			// otherwise, display each array element as its own tag (to support the other optional tags)
			foreach($url as $tag=>$val){
				?><<?=$tag?>><?=$val?></<?=$tag?>><?php
			}
		}?>

	</url>
<?php }?>
</urlset>
