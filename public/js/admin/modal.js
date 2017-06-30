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

$(function(){
	// close the modal if you click a modal overlay
	$('body').on('click', '.modal-overlay', modal.close);

	// close the modal if you press escape (keyCode 27)
	$('body').on('keydown', function(e){
		if(e.keyCode==27) modal.close();
	});
});