var artcategories = {};

artcategories.feed = function(){
	$.get('/admin/artcategories/feed', function(response){
		$('#artcategories').html(response);
	});
};

$(function(){
	if(location.href.match(/\/admin\/artcategories\/?/)){
		artcategories.feed();
	}
});