chrome.runtime.onMessage.addListener(
	function (request, sender, sendResponse) {
		var cookieValue = {},
			cookieUrl = 'http://wikia.com/',
			cookieName = 'time_machine';

		// Existing cookie
		chrome.cookies.get(
			{
				'url': cookieUrl,
				'name': cookieName
			},
			function( cookie ) {
				if ( cookie ) {
					cookieValue = JSON.parse( cookie.value );
				}

				cookieValue[ request.subdomain ] = {
					'timestamp': request.timestamp,
					'season': request.season,
					'episode': request.episode
				};

				chrome.cookies.set(
					{
						'url': cookieUrl,
						'name': cookieName,
						'value': JSON.stringify( cookieValue )
					}
				);
			}
		);

		sendResponse();
	}
);
