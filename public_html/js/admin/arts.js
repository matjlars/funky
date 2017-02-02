var arts = {};

arts.feed = function(){
	$.get('/admin/arts/feed', function(response){
		$('#arts').html(response);
	});
};

$(function(){
	// index page:
	if(location.href.match(/\/admin\/arts\/?/)){
		arts.feed();
	}
	// edit page:
	if(location.href.match(/\/admin\/arts\/edit/)){
		tabs.onload.push(function(){
			$('input[type=hidden][name=mainimage]').change(function(){
				tabs.save();
			});
		});
	}
});