<section>
	<nav>
		<a href="<?=site()->path->url('admin/admin/validator')?>"<?=(site()->path->iscurrent('admin/admin/validator'))?' class="active"':''?>>Site Validator</a>
		<a href="<?=site()->path->url('admin/admin/admintools')?>"<?=(site()->path->iscurrent('admin/admin/admintools'))?' class="active"':''?>>Admin Tools</a>
		<a href="<?=site()->path->url('admin/admin/users')?>"<?=(site()->path->iscurrent('admin/admin/users'))?' class="active"':''?>>Users</a>
		<a href="<?=site()->path->url('admin/admin/logs')?>"<?=(site()->path->iscurrent('admin/admin/logs'))?' class="active"':''?>>Logs</a>
		<a href="<?=site()->path->url('admin/admin/config')?>"<?=(site()->path->iscurrent('admin/admin/config'))?' class="active"':''?>>Config Vars</a>
		<a href="<?=site()->path->url('admin/admin/sitemap')?>"<?=(site()->path->iscurrent('admin/admin/sitemap'))?' class="active"':''?>>Sitemap</a>
	</nav>
</section>
