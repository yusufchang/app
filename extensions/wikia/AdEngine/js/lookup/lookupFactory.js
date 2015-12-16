/*global define*/
define('ext.wikia.adEngine.lookup.lookupFactory', [
	'ext.wikia.adEngine.adTracker',
	'wikia.log'
], function (adTracker, log) {
	'use strict';

	function create(module) {
		var called = false,
			response = false,
			timing;

		function call(skin) {
			log('call', 'debug', module.logGroup);

			if (!Object.keys || (typeof module.isEnabled === 'function' && !module.isEnabled())) {
				log(['call', 'Module is not enabled/supported', module.name], 'debug', module.logGroup);
				return;
			}

			timing = adTracker.measureTime(module.name, {}, 'start');
			timing.track();

			module.call(skin, onResponse);
			called = true;
		}

		function onResponse() {
			log('onResponse', 'debug', module.logGroup);

			timing.measureDiff({}, 'end').track();
			module.calculatePrices();
			response = true;

			timing.track(module.name + '/lookup_end', module.getPrices(), 0);
		}

		function wasCalled() {
			log(['wasCalled', called], 'debug', module.logGroup);
			return called;
		}

		function trackState(providerName, slotName, params) {
			log(['trackState', response, providerName, slotName], 'debug', module.logGroup);
			var category,
				encodedParams,
				eventName;

			if (!module.isSlotSupported(slotName)) {
				log(['trackState', 'Not supported slot', slotName], 'debug', module.logGroup);
				return;
			}

			eventName = response ? 'lookup_success' : 'lookup_error';
			category = module.name + '/' + eventName + '/' + providerName;
			encodedParams = module.encodeParamsForTracking(params);

			timing.track(category, slotName, 0, encodedParams || 'nodata');
		}

		function getSlotParams(slotName) {
			log(['getSlotParams', slotName, response], 'debug', module.logGroup);

			if (!response || !module.isSlotSupported(slotName)) {
				log(['getSlotParams', 'No response yet or slot is not supported', slotName], 'debug', module.logGroup);
				return {};
			}

			return module.getSlotParams(slotName);
		}

		// needed only for selenium tests
		function hasResponse() {
			log(['hasResponse', response], 'debug', module.logGroup);
			return response;
		}

		return {
			call: call,
			getSlotParams: getSlotParams,
			trackState: trackState,
			wasCalled: wasCalled,
			hasResponse: hasResponse
		};
	}

	return {
		create: create
	};
});
