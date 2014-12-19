require(['jquery'], function($) {
	'use strict';

	/* global Chart */

	var chart;

	function updateChart(data) {
		for(var i = 0; i < 7; i++) {
			chart.datasets[0].points[i].value = data[i];
		}
		chart.update();
	}

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

		$tabs.on('click', '[data-wiki-id]', function(e) {
			var $t = $(this);
			$t.parent().find('tr').removeClass('active');

			$.nirvana.sendRequest({
				controller: 'ArticlesApi',
				method: 'getPageviews',
				format: 'json',
				type: 'GET',
				data: {
					articleId: $t.data('article-id'),
					wikiId: $t.data('wiki-id')
				},
				callback: function (response) {
					var data = [
						Math.random(),
						Math.random(),
						Math.random(),
						Math.random(),
						Math.random(),
						Math.random(),
						Math.random()
					];

					for(var i = 0; i < response.items.length; i++) {
						data[i] = response.items[i].pageviews;
					}

					$t.addClass('active');
					updateChart(data.reverse());
				}
			});


		});

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
					data: [7, 6, 5, 4, 3, 2, 1]
				}
			]
		};
		var ctx = document.getElementById("chart").getContext("2d");
		chart = new Chart(ctx).Line(data, {});
		updateChart([1,2,3,4,5,6,7]);
	});
});
