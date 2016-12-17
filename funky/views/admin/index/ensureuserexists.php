<h2>Create First User</h2>
<p>It is highly recommended you make this first user be the "adminadmin" so you can use that user to manage other users.</p>

<form method="post" action="/admin/index">
	<div class="field"><?f()->load->view('fields/text/view', ['field'=>$user->field('email')])?></div>
	<div class="field"><?f()->load->view('fields/password/view', ['field'=>$user->field('password')])?></div>
	<div class="field"><?f()->load->view('fields/set/view', ['field'=>$user->field('roles')])?></div>
	<div class="field">
		<input type="submit" value="Set up first user"/>
	</div>
</form>