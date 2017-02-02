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

imagefield.initfields = function(){
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
	tabs.onload.push(imagefield.initfields);
}
// init image fields on page load:
$(imagefield.initfields);