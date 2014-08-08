$(function () {
	'use strict';

	var TimeMachine = {

		// Pretend-immutable
		COOKIE_NAME: 'time_machine',

		bar: false,

		show: function () {
			var subdomain = window.location.hostname.split('.')[0],
				data = $.cookie(this.COOKIE_NAME),
				timeMachineData,
				wikiData = '',
				viewType = '',
				referrer = document.referrer.split('/')[2];

			if (data) {
				timeMachineData = JSON.parse(data);
				wikiData = timeMachineData[subdomain];
			}

			// If we have data for this wiki use the status view.  Otherwise use the
			// activation view.
			if (wikiData) {
				viewType = 'status';
			} else if ( referrer.match(/google\.com$/) ) {
				viewType = wikiData ? 'status' : 'activation';
			}

			$.nirvana.getJson('TimeMachine', 'index', { view: viewType })
				.done(function (data) {

					if (viewType === 'status') {
						this.bar = $('<div>', {id: 'TimeMachine'}).
							html(data.content).
							insertAfter('#WikiaHeader').
							find( '.close' ).on( 'click', function () {
								TimeMachine.clearCookie( window.location.hostname.split('.')[0] );
								window.location.reload();
							} );
					} else if (viewType === 'activation') {
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
		},

		clearCookie: function () {
			var cookies = document.cookie.split( ';' ),
				host = window.location.hostname.split( '.' ),
				newValue = '';

			for ( var i in cookies ) {
				// Search through all cookies for the cookie
				if ( cookies[i].trim().indexOf( this.COOKIE_NAME ) === 0 ) {
					// Convert the cookie value to an object
					var cookieObj = JSON.parse( cookies[i].substr( cookies[i].indexOf('=') + 1 ) );
					// Remove the setting for the specified wiki
					delete cookieObj[host[0]];
					// Convert the object back to a string
					newValue = JSON.stringify( cookieObj );
					break;
				}
			}

			// Set the new value of the cookie (might be "{}")
			document.cookie = this.COOKIE_NAME + '=' + newValue + ';path=/;domain=.' + host[host.length - 2] + '.' + host[host.length - 1];
		}
	}

	TimeMachine.init();

});
