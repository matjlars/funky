<header>
	<h2>Database</h2>
	<button type="button" onclick="getmigrations()">Refresh Migrations</button>
</header>

<section id="migrations"></section>

<section>
	<h3>SQL Runner</h3>
	<textarea id="sql" rows="5" cols="50"></textarea>
	<button type="button" onclick="runsql()">Run SQL</button>
	<div id="sql-results"></div>
</section>

<script>
function getmigrations(){
	$('#migrations').html('<p>Loading Migrations...</p>');
	$.get('/admin/admin/database/migrations', function(response){
		$('#migrations').html(response);
	});
}
function runmigration(button){
	var $button = $(button);
	var sql = $button.closest('tr').find('td.has-sql').html();
	$('#sql').val(sql);
	runsql();
}
function runsql(){
	$('#sql-results').html('');
	var data = {};
	data.sql = $('#sql').val();

	$.ajax({
		url:'/admin/admin/database/query',
		method:'post',
		data:data,
		success:function(response){
			$('#sql-results').html(response);
		},
		error:function(jqXHR){
			$('#sql-results').html(jqXHR.responseText);
		},
		complete:function(){
			getmigrations();
		},
	});
}

$(function(){
	// get migrations right off the bat
	getmigrations();
	// get migrations again if the window is re-focused
	// this is for if you switch tabs/windows to change something in phpMyAdmin or something
	$(window).on('focus', getmigrations);
});
</script>
