console.log('Xbox One Snap loaded!');

(function () {
	'use strict';

	console.log('Xbox One Snap running!');

	function onResizeHandler () {
		var widthTreshold = 500,
			domain = window.location.host.substring(
				window.location.host.lastIndexOf('.', window.location.host.lastIndexOf('.') - 1) + 1
			);

		if (window.innerWidth <= widthTreshold) {
			window.document.cookie =
				'useskin=mercury;expires=Fri, 31 Dec 9999 23:59:59 GMT;path=/;domain=' + domain + ';';
			window.location.reload(true);
		}
	}

	if (1 || window.navigator.userAgent.indexOf('Xbox One') > 0) {
		window.onresize = onResizeHandler;
	}
})();
