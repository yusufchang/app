var TimeMachine = {

	bar: false,

	show: function () {
		ret = $.nirvana.getJson('TimeMachine', 'index', {})
			.done(function (data) {

				this.bar = $('<div>', {id: 'TimeMachineBar'}).
					html(data.message).
					insertAfter('#WikiaHeader');
		});
	},

	hide: function () {
		this.bar.slideUp(1000);

		// wait at least SHOW_DELAY before showing the toolbar the next time
		$.storage.set(this.STORAGE_TIMESTAMP, this.now());
	},

	init: function () {
		$.getResources ([
			function (cb) {$.getMessagesForContent('TimeMachineBar', cb);},
			$.getSassCommonURL('extensions/wikia/TimeMachine/css/TimeMachine.css')
		],
		$.proxy(this.show, this));
	}
}
