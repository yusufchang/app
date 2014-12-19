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

	var $tabs = $('.tab'),
		$buttons = $('.tabs a');

	$buttons.on('click', function(e) {
		e.preventDefault();
		var name = $(this).data('tab');

		$buttons.removeClass('active').find('[data-tab='+name+']').addClass('active');
		$tabs.removeClass('active').find('[data-tab='+name+']').addClass('active');
	}).first().click();
});
