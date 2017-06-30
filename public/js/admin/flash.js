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

$(function(){
	$('body').on('click', '.flash p', function(){
		$(this).remove();
	});
});