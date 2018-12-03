<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?foreach($urls as $url){?>
	<url>
		<?
		// if it's just a string, display that as the URL
		if(is_string($url)){
			?><loc><?=$url?></loc><?
		}else{
			// otherwise, display each array element as its own tag (to support the other optional tags)
			foreach($url as $tag=>$val){
				?><<?=$tag?>><?=$val?></<?=$tag?>><?
			}
		}?>

	</url>
<?}?>
</urlset>
