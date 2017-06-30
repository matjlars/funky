<nav>
	<a href="<?=f()->url->get('admin/admin/validator')?>"<?=(f()->url->iscurrent('admin/admin/validator'))?' class="active"':''?>>Site Validator</a>
	<a href="<?=f()->url->get('admin/admin/users')?>"<?=(f()->url->iscurrent('admin/admin/users'))?' class="active"':''?>>Users</a>
	<a href="<?=f()->url->get('admin/admin/logs')?>"<?=(f()->url->iscurrent('admin/admin/logs'))?' class="active"':''?>>Logs</a>
	<a href="<?=f()->url->get('admin/admin/config')?>"<?=(f()->url->iscurrent('admin/admin/config'))?' class="active"':''?>>Config</a>
	<a href="<?=f()->url->get('admin/admin/sitemap')?>"<?=(f()->url->iscurrent('admin/admin/sitemap'))?' class="active"':''?>>Sitemap</a>
	<a href="<?=f()->url->get('admin/admin/database')?>"<?=(f()->url->iscurrent('admin/admin/database'))?' class="active"':''?>>Database</a>
</nav>
