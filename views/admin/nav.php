<nav><?
	if(f()->access->isloggedin()){
		if(f()->access->hasrole('admin')){
			foreach(array(
				'/admin/testimonials'=>'Testimonials',
			) as $path=>$name){
				?><a href="<?=$path?>"<?=(f()->url->iscurrent($path))?' class="active"':''?>><?=$name?></a><?
			}
		}
		?><aside>
			<?if(f()->access->hasrole('dev')){?>
				<a href="/admin/admin">dev</a>
			<?}?>
			<a href="/admin/logout">Logout</a>
		</aside>
	<?}?>
</nav>
