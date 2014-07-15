/*global define*/
/*jshint camelcase:false*/
/*jshint maxdepth:4*/

(function (w) {
	'use strict';

	var params, param, value;

	function logMock() {}

	function retrieve(n) {
		var k = 'kx' + n;
		if (w.localStorage) {
			return w.localStorage[k] || '';
		}
		return '';
	}

	if (!w.Krux) {
		w.Krux = function () {
			w.Krux.q.push(arguments);
		};
		w.Krux.q = [];
	}

	w.Krux.user = retrieve('user');
	w.Krux.segments = retrieve('segs') ? retrieve('segs').split(',') : [];

	if (w.AdEngine_adLogicPageParams) {
		params = w.AdEngine_adLogicPageParams(logMock, w).getPageLevelParams();

		for (param in params) {
			if (params.hasOwnProperty(param)) {
				value = params[param];
				if (value) {
					w['kruxDartParam_' + param] = value.toString();
				}
			}
		}
	}

	define('ext.wikia.adEngine.krux', function () {
		return w.Krux;
	});

}(this));
