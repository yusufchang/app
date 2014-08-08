$(function () {
	'use strict';

	var TimeMachine = {

		bar: false,

		show: function () {
			var subdomain = window.location.hostname.split('.')[0],
				data = $.cookie('time_machine'),
				timeMachineData,
				wikiData = '';

			if (data) {
				timeMachineData = JSON.parse(data);
				wikiData = timeMachineData[subdomain];
			}

			// If we have data for this wiki use the status view.  Otherwise use the
			// activation view.
			var viewType = wikiData ? 'status' : 'activation';

			$.nirvana.getJson('TimeMachine', 'index', { view: viewType })
				.done(function (data) {

					if (viewType === 'status') {
						this.bar = $('<div>', {id: 'TimeMachine'}).
							html(data.content).
							insertAfter('#WikiaHeader');
					} else {
						this.bar = $('<div>', {id: 'TimeMachine'}).
							html(data.content).
							insertAfter('header#WikiaPageHeader');
					}
				});
		},

		hide: function () {
			this.bar.slideUp(1000);

			// wait at least SHOW_DELAY before showing the toolbar the next time
			$.storage.set(this.STORAGE_TIMESTAMP, this.now());
		},

		init: function () {
			this.show();
		}
	}

	TimeMachine.init();

});
