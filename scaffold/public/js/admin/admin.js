// a nice function that takes a jquery selector or any element
// ... and returns all [name]'d data within that element.
function getFormData(selector){
	var d = {};
	$(selector+' [name]').each(function(){
		$this = $(this);
		var shouldwrite = true;
		// exclude unchecked checkboxes:
		if($this.attr('type') == 'checkbox' && !$this.is(':checked')) shouldwrite = false;
		if($this.attr('type') == 'file') shouldwrite = false;
		
		if(shouldwrite){
			var name = $this.attr('name');
			
			// get the value:
			var val = $this.val();
			
			// get the value if it's a tinymce rich text editor:
			if(typeof(tinymce)!='undefined'){
				if($this.is('textarea')){
					// try to find a tinymce rte connected to this textarea:
					var ed = tinymce.get($this.attr('id'));
					if(ed != null){
						val = ed.getContent();
					}
				}
			}
			
			// if this name ends in [] that means it should be an array being sent:
			if(name.indexOf('[]', name.length - 2) !== -1){
				name = name.substr(0, name.length - 2); // strip the [] off the end
				// if this is the first one, define that var as an array before appending it to the array
				if(typeof(d[name])=='undefined'){
					d[name] = [];
				}
				d[name].push(val);
			}else{ // it's not an array name
				d[name] = val;
			}
		}
	});
	return d;
}

var feedpage = {};
feedpage.init = function(url, singular_noun){
	if(url.substr(-1) != '/') url += '/';
	feedpage.url = url;
	feedpage.singular_noun = singular_noun;
	feedpage.load();
};
feedpage.load = function(){
	var url = feedpage.url + 'feed';
	$.post(url, function(response){
		$('#results').html(response);
	});
};
feedpage.deactivate = function(id){
	var url = feedpage.url + 'deactivate';
	$.post(url, {id:id}, function(response){
		feedpage.response(response, 'deactivated');
	});
};
feedpage.activate = function(id){
	var url = feedpage.url + 'activate';
	$.post(url, {id:id}, function(response){
		feedpage.response(response, 'activated');
	});
};
feedpage.delete = function(id){
	if(confirm('Are you sure you want to permanently delete this '+feedpage.singular_noun+'?')){
		var url = feedpage.url + 'delete';
		$.post(url, {id:id}, function(response){
			feedpage.response(response, 'deleted');
		});
	}
};
feedpage.response = function(response, action){
	if(response == 'ok'){
		flash.success(feedpage.singular_noun + ' ' + action);
	}else{
		flash.error(response);
	}
	feedpage.load();
};
