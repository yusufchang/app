console.log('Xbox One Snap loaded!');

(function () {
	'use strict';

	console.log('Xbox One Snap running!');

	function onResizeHandler () {
		var widthTreshold = 500,
			wl = window.location,
			queryString = (wl.search ? wl.search + '&' : '?') + 'useskin=mercury';

		if (window.innerWidth <= widthTreshold) {
			wl.replace(wl.protocol + '//' + wl.host + wl.pathname + queryString + wl.hash);
		}
	}

	if (1 || window.navigator.userAgent.indexOf('Xbox One') > 0) {
		onResizeHandler();
		window.onresize = onResizeHandler;
	}
})();
