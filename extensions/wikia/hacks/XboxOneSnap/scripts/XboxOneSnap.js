console.log('Xbox One Snap loaded!');

(function () {
	'use strict';

	console.log('Xbox One Snap running!');

	function onResizeHandler () {
		var widthTreshold = 500,
			queryString = (window.location.search ? window.location.search + '&' : '?') + 'useskin=mercury';

		if (window.innerWidth <= widthTreshold) {
			window.location.replace(window.location.origin + window.location.pathname + queryString);
		}
	}

	if (1 || window.navigator.userAgent.indexOf('Xbox One') > 0) {
		onResizeHandler();
		window.onresize = onResizeHandler;
	}
})();
