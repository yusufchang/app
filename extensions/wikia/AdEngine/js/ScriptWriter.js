/*global setTimeout*/
var ScriptWriter = function(log, postscribe, document) {
	'use strict';

	var module = 'ScriptWriter';

	function beforeWrite(str) {
		return str.replace(/<\/embed>/gi, '');
	}

	function escape(str) {
		return str.replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	}

	function injectScriptByUrl(elementId, url, callback) {
		log('injectScriptByUrl: injecting ' + url + ' to slot: ' + elementId, 5, module);
		postscribe(
			'#' + elementId,
			'<script src="' + escape(url) + '"></script>',
			{
				beforeWrite: beforeWrite,
				done: function() {
					log('DONE injectScriptByUrl: (' + url + ' to slot: ' + elementId + ')', 5, module);
					if (typeof callback === 'function') {
						callback();
					}
				}
			}
		);
	}

	function injectScriptByText(elementId, text, callback) {
		log('injectScriptByText: injecting script ' + text.substr(0, 20) + '... to slot: ' + elementId, 5, module);
		postscribe(
			'#' + elementId,
			'<script>' + text + '</script>',
			{
				beforeWrite: beforeWrite,
				done: function() {
					log('DONE injectScriptByText: (' + text.substr(0, 20) + '... to slot: ' + elementId + ')', 5, module);
					if (typeof callback === 'function') {
						callback();
					}
				}
			}
		);
	}

	function callLater(callback) {
		log('callLater registered', 5, module);
		setTimeout(function () {
			log('Calling callLater now', 7, module);
			callback();
			log('Actual callLater called', 7, module);
		}, 0);
	}

	return {
		injectScriptByUrl: injectScriptByUrl,
		injectScriptByText: injectScriptByText,
		callLater: callLater
	};
};
