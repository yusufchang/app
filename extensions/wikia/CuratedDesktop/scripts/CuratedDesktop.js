'use strict';

$(function(){
	$('.featured-cc').slippry({
		adaptiveHeight: false,
		pager: false,
		onSliderLoad: function() {
			$('.featured-cc').removeClass('loading');
		}
	});

	var activeSection = 0;

	function activate(newActiveSession) {
		console.log('activate', newActiveSession);

		$('[data-section=' + activeSection + ']').hide();
		$('[data-section=' + newActiveSession + ']').show();
		activeSection = newActiveSession;
	}

	$('.curated-wrapper--bottom').on('mouseleave', function(){
		activate(0);
	}).on('click', '.curated-item-wrapper.section', function(){
		var id = $(this).data('activate');
		activate( id );
	});
});
