<h2>Users <a class="green button" href="/admin/admin/users/edit/0">Create New User</a></h2>
<table>
	<colgroup>
		<col/>
		<col style="width:20rem;"/>
		<col style="width:10rem;"/>
	</colgroup>
	<thead>
		<tr>
			<th>Email Address</th>
			<th>Roles</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($users as $user){?>
			<tr>
				<td><?=$user->email?></td>
				<td><?=implode(', ', $user->roles->get())?></td>
				<td>
					<a class="button" href="/admin/admin/users/edit/<?=$user->id?>">edit</a>
					<a class="red button" href="/admin/admin/users/delete/<?=$user->id?>">delete</a>
				</td>
			</tr>
		<?php }?>
	</tbody>
</table>
