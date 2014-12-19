require(['jquery'], function($) {
	'use strict';

	/* global c3 */

	//c3.generate({
	//	data: {
	//		columns: [
	//			['data1', 30, 200, 100, 400, 150, 250],
	//			['data2', 50, 20, 10, 40, 15, 25],
	//			['data3', 130, 150, 200, 300, 200, 100]
	//		]
	//	}
	//});

	$(function(){
		var $tabs = $('.tab-contents'),
			$buttons = $('.trending-tabs a');

		$buttons.on('click', function(e) {
			e.preventDefault();

			$buttons.removeClass('active');
			$tabs.removeClass('active');

			var name = $(this).addClass('active').data('tab');

			$tabs.filter('[data-tab='+name+']').addClass('active');
		}).first().click();
	});
});
