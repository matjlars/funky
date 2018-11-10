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


var markdown = {};
markdown.help = function(){
	var html = '';
	html += '<header><h2>Markdown Help</h2></header>';
	html += '<section class="content">';
	html += '<p>Markdown is a format of text that allows both easy editing and clean data.</p>';
	html += '<p>This is how you can control the format of your text</p>';
	html += '<pre>';
	html += '*bold*\n';
	html += '**italics**\n';
	html += '***bold and italics***\n';
	html += '</pre>';
	html += '<p>This is how you can make a bulleted list.</p>';
	html += '<pre>';
	html += '- Unordered\n';
	html += '- List\n';
	html += '- Of\n';
	html += '- Items\n';
	html += '</pre>';
	html += '<p>This is how you can make a numbered list. You can number each line if you want, but using all 1s allows easy editing.</p>';
	html += '<pre>';
	html += '1. Ordered\n';
	html += '1. List\n';
	html += '1. Of\n';
	html += '1. Items\n';
	html += '</pre>';
	html += '<p>This is how to make a link.</p>';
	html += '<pre>';
	html += '[Link Text](https://www.runwayanalytics.com)\n';
	html += '</pre>';
	html += '<p>This is how you can display images you upload on the <a href="/admin/images">Images Page</a>. The number here is the corresponding "id" for your image.</p>';
	html += '<pre>';
	html += '[img.1234]';
	html += '</pre>';
	html += '</section>';

	html += '<footer>';
	html += '<a class="button" onclick="modal.close();">Got It</a> ';
	html += 'Feel free to contact Web Elements, LLC. with any questions.';
	html += '</footer>';
	modal.html(html);
};
