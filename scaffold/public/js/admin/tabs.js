var tabs = {};
tabs.ajax = null;
// an array of functions that will be called after every tab load
tabs.onload = [];


tabs.url = function($tab){
	return $tab.attr('href') + '/' + $tabs.attr('data-id');
}
tabs.load = function($tab){
	$tabs = $tab.closest('.tabs');
	
	// update the .active class
	$tabs.find('nav>a').removeClass('active');
	$tab.addClass('active');
	
	// get the new tab content
	if(tabs.ajax) tabs.ajax.abort();
	tabs.ajax = $.get(tabs.url($tab), function(response){
		$tabs.find('.content').html(response);
		for(var i in tabs.onload) tabs.onload[i]();
	});
};
tabs.save = function(onValidSave){
	$tab = $('.tabs a.active');
	var url = tabs.url($tab);
	var data = getFormData('.tabs .content');
	if(tabs.ajax) tabs.ajax.abort();
	tabs.ajax = $.post(url, data, function(response){
		if(response == ''){
			flash.success('Saved!');
		}else{
			flash.error(response);
		}
	});
};

// reloads all tabs on the page
tabs.reload = function(){
	$('div.tabs a.active').each(function(){
		tabs.load($(this));
	});
};

$(function(){
	// click on a tab to load a tab
	$('.tabs a').click(function(){
		tabs.load($(this));
		return false;
	});
	
	// load the active tab, or the first one
	$tab = $('.tabs a.active');
	if(!$tab.length) $tab = $('.tabs a');
	$tab.first().each(function(){
		tabs.load($(this));
	});
});
