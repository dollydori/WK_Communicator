var TICKER = false;
var intervalKey = null;
var t_idle_start = 0;
var sound;

function onResize() {
	if( $('.image').length > 0 ) $('.image').height( $(window).height() - parseInt($('.content').css('top')) - $('.content').height() -30 );
}

function initSlide() {
	$.ajax({
		type: 'POST',
		url: 'getdata.php',
		data: { record: 'last', curr: 0 }
	}).done(function(data) {
		if( $('.id').length > 0) {
			var _id = $('<div></div>').append(data).find('.id:first').html();
			if( $('.id:first').html() == _id ) return;
		}

		$('#display').removeClass('loading_img');
		$('#display').html(data);

		sound.play();

		if( $('.image').length > 0 ) {
			setBgImg('.image:first', $('.image:first').attr('title'));
		}
		$('#to_next').hide();
		layoutContent();

	});
}

function checkIdle() {
	if((new Date()).getTime() - t_idle_start > 10*1000) {	// if idle > 10 seconds
		t_idle_start = 0;
		window.clearInterval(intervalKey);
		intervalKey = window.setInterval(initSlide, 2000);	
	}
}

function toSlide(key) {
	if(t_idle_start == 0) {
		window.clearInterval(intervalKey);
		intervalKey = window.setInterval(checkIdle, 5000);
	}
	t_idle_start = (new Date()).getTime();

	$('#display').fadeOut(500);
	$.ajax({
		type: 'POST',
		url: 'getdata.php',
		data: { record: key, curr: parseInt($('.id:first').html()) }
	}).done(function(data) {
		$('#display').removeClass('loading_img');
		$('#display').html(data);
		$('#display').fadeIn(500);
		if( $('.image').length > 0 ) {
			setBgImg('.image:first', $('.image:first').attr('title'));
		}
		switch( $('.id:first').attr('title') ) {
			case 'last':
				$('#to_next').hide();
				break;
			case 'first':
				$('#to_prev').hide();
				break;
			default:
				$('#to_prev').show();
				$('#to_next').show();
				break;
		}
		layoutContent();
	});
}

function layoutContent() {
	var w = textWidth($('.content').html(), $('.content').attr('class'), $('.content').attr('id'));
	TICKER = false;
	$('.content').clearQueue().removeAttr('style');
	if( w <= $('.content').width() )
		$('.content').css('text-align', 'center');
	else {
		// ticker
		TICKER = true;
		runContentTicker();
	}

	if( $('.image').length == 0 )
		$('.content').css('top', Math.floor( ($(window).height()-$('.content').height()) /2) - $('.name').height());
}

function runContentTicker() {
	var w = textWidth($('.content').html(), $('.content').attr('class'), $('.content').attr('id'));
	$('.content').css('margin-left', $(window).width());
	$('.content').css('width', 'auto').animate({
		'margin-left': -w-Math.floor($(window).width()*0.2)
	}, Math.floor(w*4), 'linear', function() {
		if(TICKER) runContentTicker();
	});
}

function textWidth(text, _class, _id) {
	var div = $('<div style="width:auto; display:none;"></div>');
	$(div).addClass(_class).attr('id', _id).html(text);
	$('body').append(div);
	var w = $(div).width();
	$(div).detach();
	return w;
};

function setBgImg(selector, path) {
	$('#display').addClass('loading_img');
	var img = $('<img src="'+path+'" />');
	$(img).width(1).height(1);
	$(selector).append(img);
	$(selector).waitForImages(function() {
		$(this).hide();
		$(this).css('background-image', 'url(' + $(this).find('img:first').attr('src') + ')');
		$(this).find('img:first').detach();
		$(this).fadeIn(500);
		onResize();
		$('#display').removeClass('loading_img');
	}, function(loaded, count, success) {
		//alert(loaded + ' of ' + count + ' images has ' + (success ? 'loaded' : 'failed to load') +  '.');
	});
}
