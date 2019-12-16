<h2>Config</h2>
<p>Here, you can edit any config value. Here are some pointers:</p>
<ul>
	<li>All this form does is edit the /config.txt file</li>
	<li>Be careful about editing the database values. If you get them wrong, the system will not be able to log you in on your next request.</li>
	<li>To delete a key/value pair altogether, simply make the value empty and save it.</li>
</ul>

<form action="/admin/admin/config" method="post">
	<h3>Existing Config</h3>
	<?php foreach($vars as $key=>$val){?>
		<div class="field">
			<label for="<?=$key?>"><?=$key?></label>
			<input type="text" name="config[<?=$key?>]" id="<?=$key?>" value="<?=$val?>"/>
		</div>
	<?php }?>
	<h3>New Config</h3>
	<p>Enter a new key and a new value here if you want to add a new key/value pair</p>
	<div class="field">
		<label for="newkey">New Key</label>
		<input type="text" name="newkey" id="newkey"/>
	</div>
	<div class="field">
		<label for="newval">New Value</label>
		<input type="text" name="newval" id="newval"/>
	</div>
	<input type="submit" name="saveconfig" value="Save Config"/>
</form>
