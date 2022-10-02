<header>
	<h2>Create First User</h2>
	<button type="save" form="edit-form">Save</button>
</header>

<p>You should make this first user have the "dev" role, so you can use that user to manage other users.</p>

<form method="post" action="/admin/index" id="edit-form">
	<div class="field"><?=f()->view->load('fields/text/view', ['field'=>$user->email])?></div>
	<div class="field"><?=f()->view->load('fields/password/view', ['field'=>$user->password])?></div>
	<div class="field"><?=f()->view->load('fields/set/view', ['field'=>$user->roles])?></div>
</form>