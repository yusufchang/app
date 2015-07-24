'use strict';

$(function(){
	$('.featured-cc').slippry({
		adaptiveHeight: false,
		pager: false,
		onSliderLoad: function() {
			$('.featured-cc').removeClass('loading');
		}
	});
});
