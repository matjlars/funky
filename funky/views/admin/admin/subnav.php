<nav>
	<a href="<?=f()->path->url('admin/admin/validator')?>"<?=(f()->path->iscurrent('admin/admin/validator'))?' class="active"':''?>>Site Validator</a>
	<a href="<?=f()->path->url('admin/admin/users')?>"<?=(f()->path->iscurrent('admin/admin/users'))?' class="active"':''?>>Users</a>
	<a href="<?=f()->path->url('admin/admin/logs')?>"<?=(f()->path->iscurrent('admin/admin/logs'))?' class="active"':''?>>Logs</a>
	<a href="<?=f()->path->url('admin/admin/configvars')?>"<?=(f()->path->iscurrent('admin/admin/configvars'))?' class="active"':''?>>Config Vars</a>
	<a href="<?=f()->path->url('admin/admin/sitemap')?>"<?=(f()->path->iscurrent('admin/admin/sitemap'))?' class="active"':''?>>Sitemap</a>
	<a href="<?=f()->path->url('admin/admin/database')?>"<?=(f()->path->iscurrent('admin/admin/database'))?' class="active"':''?>>Database</a>
</nav>
