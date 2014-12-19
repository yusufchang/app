require(['jquery'], function($) {
	'use strict';

	/* global Chart */

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

		var data = {
			labels: [ '-6d', '-5d', '-4d', '-3d', '-2d', '-1d', 'Today'],
			datasets: [
				{
					label: "Dataset",
					fillColor: "rgba(220,220,220,0.2)",
					strokeColor: "rgba(220,220,220,1)",
					pointColor: "rgba(220,220,220,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: [1, 2, 3, 4, 5, 6, 7]
				}
			]
		};
		var ctx = document.getElementById("chart").getContext("2d");
		var myLineChart = new Chart(ctx).Line(data, {
		});

	});
});
