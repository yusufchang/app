$(function () {
	'use strict';

	var TimeMachine = {

		bar: false,

		show: function () {
			// Read cookie & determine whether the user is in the Time Machine or not and set this
			// variable accordingly
			var viewType = 'activation';// 'status'; // or 'activation'

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
