/*global define*/
/*jshint camelcase:false*/
/*jshint maxdepth:5*/
define('ext.wikia.adEngine.lookup.rubiconFastlane', [
	'ext.wikia.adEngine.adTracker',
	'ext.wikia.adEngine.utils.adLogicZoneParams',
	'wikia.document',
	'wikia.log',
	'wikia.window'
], function (adTracker, adLogicZoneParams, doc, log, win) {
	'use strict';

	var logGroup = 'ext.wikia.adEngine.lookup.rubiconFastlane',
		rubiconResponse = false,
		rubiconTiming,
		slots = {},
		config = {
			oasis: {
				TOP_RIGHT_BOXAD: [[300, 250]]
			},
			mercury: {}
		},
		called = false;

	function onResponse() {
		rubiconResponse = true;
	}

	function getSlots(skin) {
		return config[skin];
	}

	function defineSlot(slotName, sizes) {
		var slotPath = [
				'/5441',
				'wka.' + adLogicZoneParams.getSite(),
				adLogicZoneParams.getMappedVertical(),
				'',
				adLogicZoneParams.getPageType()
			].join('/');

		win.rubicontag.cmd.push(function () {
			var slot = win.rubicontag.defineSlot(
					'wikia_gpt' + slotPath + '/gpt/' + slotName,
					sizes,
					'wikia_gpt' + slotPath + '/gpt/' + slotName
			).setPosition('atf');
			slots[slotName] = slot;
			win.rubicontag.run(onResponse, {
				slots:[slot]
			});
		});
	}

	function defineSlots(skin) {
		var definedSlots = getSlots(skin);
		Object.keys(definedSlots).forEach(function (slotName) {
			defineSlot(slotName, definedSlots[slotName]);
		});
	}

	function call(skin) {
		log('call', 'debug', logGroup);

		var rubicon = doc.createElement('script'),
			node = doc.getElementsByTagName('script')[0];

		win.rubicontag = win.rubicontag || {};
		win.rubicontag.cmd = win.rubicontag.cmd || [];

		rubiconTiming = adTracker.measureTime('rubicon_fastlane', {}, 'start');
		rubiconTiming.track();

		rubicon.async = true;
		rubicon.type = 'text/javascript';
		rubicon.src = '//ads.rubiconproject.com/header/7450.js';

		node.parentNode.insertBefore(rubicon, node);
		defineSlots(skin);
		called = true;
	}

	function wasCalled() {
		log(['wasCalled', called], 'debug', logGroup);
		return called;
	}

	function getSlotParams(slotName) {
		var targeting;
		if (rubiconResponse && slots[slotName]) {
			targeting = slots[slotName].getAdServerTargeting();
			console.log('Targeting', slotName, targeting);
			return targeting;
		}
		return {};
	}

	return {
		call: call,
		getSlotParams: getSlotParams,
		trackState: function () {},
		wasCalled: wasCalled
	};
});
