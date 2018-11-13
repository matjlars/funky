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
	html += '<p>You can make headers (in HTML terms, h1-h6 tags). Please note that you almost certainly do not want to make a header 1 because there should only be one h1 tag on a final page, and there is probably an h1 already on the page.</p>';
	html += '<pre>';
	html += '# This is a header 1\n';
	html += '## This is a header 2\n';
	html += '### This is a header 3\n';
	html += '#### This is a header 4\n';
	html += '##### This is a header 5\n';
	html += '###### This is a header 6';
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


var imagefield = {};

// actually performs the file upload
imagefield.upload = function($filefield){
	if($filefield.attr('type')!='file') throw 'uploadFile() parameter 2 needs to be the selector to an input[type=file]';
	$imagefield = $filefield.closest('.imagefield');
	
	// pack up the files in a nice FormData object:
	var file = $filefield[0].files[0];
	var data = new FormData();
	data.append('image', file);
	
	// perform the ajax request:
	$.ajax({
		url:'/admin/images/upload',
		type:'POST',
		data:data,
		cache:false,
		dataType:'html',
		processData:false,
		contentType:false,
		success:function(response){
			response = JSON.parse(response);
			$imagefield.find('input[type=hidden]').val(response.id).change();
			$imagefield.find('img').attr('src', response.url);
		},
		error:function(response){
			flash.error(response.responseText);
		},
		complete:function(){
			modal.close();
		},
	});
};

imagefield.init = function(){
	// automatically upload the image when one is selected
	$('.imagefield input[type=file]').on('change', function(){
		$filefield = $(this);
		$imagefield = $filefield.closest('.imagefield');
		$imagefield.addClass('loading');
		imagefield.upload($filefield, {}, function(){
			// nothing special on success
		}, function(response){
			// error. flash an error and clear the file
			flash.error(response.responseText);
		}, function(){
			// complete. either way, remove the loading class
			$imagefield.removeClass('loading');
		});
	});
	// when you click an image, bring up the upload dialog
	$('.imagefield img').on('click', function(){
		var $img = $(this);
		var $imagefield = $img.closest('.imagefield');
		var $hiddeninput = $imagefield.find('input[type=hidden]');
		var name = $hiddeninput.attr('name');
		if($img.attr('src') == ''){
			imagefield.openuploaddialog(name);
		}else{
			// show a modal with the full image and some options
			var html = '';
			html += '<header>Image Preview</header>';
			html += '<div class="content">';
			html += '<img src="'+$img.attr('src')+'"/>';
			html += '</div>';
			html += '<footer>'
			html += '<a class="button" onclick="modal.close();">Cancel</a>';
			html += '<a class="button" onclick="imagefield.delete(\''+name+'\');">Delete</a>';
			html += '<a class="button" onclick="imagefield.openuploaddialog(\''+name+'\');">Replace</a>';
			html += '</footer>';
			modal.html(html);
		}
	});
};
imagefield.delete = function(name){
	$hiddeninput = $('input[type=hidden][name="'+name+'"]');
	$imagefield = $hiddeninput.closest('.imagefield');
	$imagefield.find('img').attr('src', '');
	$hiddeninput.val(0).change();
	modal.close();
};
imagefield.openuploaddialog = function(name){
	$('input[type=hidden][name="'+name+'"]').closest('.imagefield').find('input[type=file]').click();
};


// make the tabs system automatically load image fields
if(typeof(tabs) != 'undefined'){
	tabs.onload.push(imagefield.init);
}


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
tabs.init = function(){
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
};


var modal = {};
modal.onopen = [];
modal.onclose = [];

modal.html = function(html, callback){
	modal.close();
	$('body').append('<div class="modal-overlay"></div>');
	$('body').append('<div class="modal">'+html+'</div>');
	$('.modal input[type=text]').first().focus();
	for(var i in modal.onopen) modal.onopen[i]();
	if(typeof(callback) == 'function') callback();
};
modal.get = function(url, callback){
	$.get(url, function(html){
		modal.html(html);
		if(typeof(callback)=='function') callback(html);
	});
};
modal.close = function(callback){
	if($('.modal').length){
		$('.modal').remove();
		$('.modal-overlay').remove();
		for(var i in modal.onclose) modal.onclose[i]();
		if(typeof(callback) == 'function') callback();
	}
};
modal.init = function(){
	// close the modal if you click a modal overlay
	$('body').on('click', '.modal-overlay', modal.close);

	// close the modal if you press escape (keyCode 27)
	$('body').on('keydown', function(e){
		if(e.keyCode==27) modal.close();
	});
};


var flash = {};

flash.show = function(type, msg){
	var html = '<p class="'+type+'">'+msg+'</p>';
	$container = $('.flash');
	// create a .flash container if one doesn't exist
	if($container.length==0){
		$('body').append('<div class="flash"></div>');
		$container = $('<div class="flash"></div>').appendTo('body');
	}
	$(html).appendTo($container).delay(7000).fadeOut('fast');
};
flash.message = function(msg){
	flash.show('message', msg);
};
flash.error = function(msg){
	flash.show('error', msg);
};
flash.success = function(msg){
	flash.show('success', msg);
}
flash.init = function(){
	$('body').on('click', '.flash p', function(){
		$(this).remove();
	});
};

$(function(){
	imagefield.init();
	flash.init();
	tabs.init();
	modal.init();
});

