/*global define*/
define('ext.wikia.adengine.template.skin', ['wikia.document', 'wikia.window', 'wikia.log'], function (document, window, log) {
	'use strict';

	var logGroup = 'ext.wikia.adengine.template.skin';

	function hexToRgba(hex, opacity) {
		log(['hexToRgba', hex, opacity], 'debug', logGroup);
		hex = hex.toString();

		if (hex.length === 3) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}

		if (hex.length !== 6) {
			return 'rgba(0,0,0,0)';
		}

		var r = parseInt(hex[0] + hex[1], 16),
			g = parseInt(hex[2] + hex[3], 16),
			b = parseInt(hex[4] + hex[5], 16),
			rgba = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';

		log(['hexToRgba', hex, opacity, rgba], 'debug', logGroup);

		return rgba;
	}

	/**
	 * @param params {
	 *   skinImage
	 *   backgroundColor
	 *   destUrl
	 *   pixels
	 * }
	 */
	function show(params) {
		log(params, 'debug', logGroup);

		var body = document.getElementsByTagName('body')[0],
			head = document.getElementsByTagName('head')[0],
			style = document.createElement('style'),
			adSkin = document.getElementById('ad-skin'),
			adSkinStyle = adSkin.style,
			wikiaSkin = document.getElementById('WikiaPageBackground'),
			wikiaSkinStyle = wikiaSkin.style,
			i,
			len,
			pixelUrl,
			pixelElement,
			gradient0 = hexToRgba(params.backgroundColor, 0),
			gradient1 = hexToRgba(params.backgroundColor, 1);

		style.textContent = 'body {' +
			'  background:' + gradient1 + ';' +

			'} body:after,body:before {' +
			'  background-image:url(' + params.skinImage + ');' +
			'  height:800px;' +
			'  width:850px;' +

			'} .background-image-gradient:after {' +
			'  background-color:' + gradient0 + ';' +
			'  background-image:-webkit-linear-gradient(right,' + gradient0 + ' 0%,' + gradient1 + ' 100%);' +
			'  background-image:linear-gradient(to left,' + gradient0 + ' 0%,' + gradient1 + ' 100%);' +

			'} .background-image-gradient:before {' +
			'  background-color:' + gradient0 + ';' +
			'  background-image:-webkit-linear-gradient(left,' + gradient0 + ' 0%,' + gradient1 + ' 100%);' +
			'  background-image:linear-gradient(to right,' + gradient0 + ' 0%,' + gradient1 + ' 100%);' +
			'}';

		head.appendChild(style);
		body.className += ' has-responsive-ad-skin';
		window.wgAdSkinPresent = true;

		adSkinStyle.position = 'fixed';
		adSkinStyle.height = '100%';
		adSkinStyle.width = '100%';
		adSkinStyle.left = 0;
		adSkinStyle.top = 0;
		adSkinStyle.zIndex = 0;
		adSkinStyle.cursor = 'pointer';

		wikiaSkinStyle.opacity = 1;
		log('Skin set', 5, logGroup);

		adSkin.onclick = function (e) {
			log('Click on skin', 'user', logGroup);
			window.open(params.destUrl);
		};

		if (params.pixels) {
			for (i = 0, len = params.pixels.length; i < len; i += 1) {
				pixelUrl = params.pixels[i];
				if (pixelUrl) {
					log('Adding tracking pixel ' + pixelUrl, 'debug', logGroup);
					pixelElement = document.createElement('img');
					pixelElement.src = pixelUrl;
					pixelElement.width = 1;
					pixelElement.height = 1;
					adSkin.appendChild(pixelElement);
				}
			}
		}

		log('Pixels added', 'debug', logGroup);
	}

	return {
		show: show
	};
});
