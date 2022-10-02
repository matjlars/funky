<h2>Create First User</h2>
<p>It is highly recommended you make this first user be a "dev" so you can use that user to manage other users.</p>

<form method="post" action="/admin/index">
	<div class="field"><?=f()->view->load('fields/text/view', ['field'=>$user->email])?></div>
	<div class="field"><?=f()->view->load('fields/password/view', ['field'=>$user->password])?></div>
	<div class="field"><?=f()->view->load('fields/set/view', ['field'=>$user->roles])?></div>
	<button type="save">Set up first user</button>
</form>