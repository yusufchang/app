/*global define*/
/*jshint camelcase:false*/
/*jshint maxdepth:5*/
define('ext.wikia.adEngine.lookup.rubiconFastlane', [
	'ext.wikia.adEngine.adContext',
	'ext.wikia.adEngine.adTracker',
	'ext.wikia.adEngine.slot.adSlot',
	'ext.wikia.adEngine.utils.adLogicZoneParams',
	'wikia.document',
	'wikia.log',
	'wikia.window'
], function (adContext, adTracker, adSlot, adLogicZoneParams, doc, log, win) {
	'use strict';

	var logGroup = 'ext.wikia.adEngine.lookup.openXBidder',
		oxResponse = false,
		rubiconTiming,
		called = false,
		priceTimeout = 't',
		config = {
			oasis: {
				TOP_LEADERBOARD: '728x90',
				TOP_RIGHT_BOXAD: '300x250',
				LEFT_SKYSCRAPER_2: '160x600',
				PREFOOTER_LEFT_BOXAD: '300x250',
				PREFOOTER_RIGHT_BOXAD: '300x250'
			},
			mercury: {
				MOBILE_IN_CONTENT: '300x250',
				MOBILE_PREFOOTER: '300x250',
				MOBILE_TOP_LEADERBOARD: '320x50'
			}
		},
		priceMap = {},
		slots = [];

	function getSlots(skin) {
		var context = adContext.getContext(),
			pageType = context.targeting.pageType,
			slotName;

		slots = config[skin];
		if (skin === 'oasis' && pageType === 'home') {
			for (slotName in slots) {
				if (slots.hasOwnProperty(slotName) && slotName.indexOf('TOP') > -1) {
					slots['HOME_' + slotName] = slots[slotName];
					delete slots[slotName];
				}
			}
		}

		return slots;
	}

	function getAds(skin) {
		var ads = [],
			size,
			slotName,
			slotPath = [
				'/5441',
				'wka.' + adLogicZoneParams.getSite(),
				adLogicZoneParams.getMappedVertical(),
				'',
				adLogicZoneParams.getPageType()
			].join('/');

		slots = getSlots(skin);
		for (slotName in slots) {
			if (slots.hasOwnProperty(slotName)) {
				size = slots[slotName];
				ads.push([
					slotPath,
					[size],
					'wikia_gpt' + slotPath + '/gpt/' + slotName
				]);
			}
		}

		return ads;
	}

	function trackState(trackEnd) {
		log(['trackState', oxResponse], 'debug', logGroup);

		var eventName,
			slotName,
			data = {};

		if (oxResponse) {
			eventName = 'lookupSuccess';
			for (slotName in priceMap) {
				if (priceMap.hasOwnProperty(slotName)) {
					data[slotName] = priceMap[slotName];
				}
			}
		} else {
			eventName = 'lookupError';
		}

		if (trackEnd) {
			eventName = 'lookupEnd';
		}

		adTracker.track(eventName + '/ox', data || '(unknown)', 0);
	}

	function onResponse() {
		rubiconTiming.measureDiff({}, 'end').track();
		log('OpenX bidder done', 'info', logGroup);
		var prices = win.OX.dfp_bidder.getPriceMap(),
			slotName,
			shortSlotName;

		for (slotName in prices) {
			if (prices.hasOwnProperty(slotName) && prices[slotName].price !== priceTimeout) {
				shortSlotName = adSlot.getShortSlotName(slotName);
				priceMap[shortSlotName] = prices[slotName].price;
			}
		}
		oxResponse = true;
		log(['OpenX bidder prices', priceMap], 'info', logGroup);

		trackState(true);
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
		rubicon.src = '//ads.rubiconproject.com/header/14062.js';

		node.parentNode.insertBefore(rubicon, node);

		defineSlots();
		called = true;
	}

	function defineSlots() {
		var slotName = 'TOP_LEADERBOARD',
			slotPath = [
				'/5441',
				'wka.' + adLogicZoneParams.getSite(),
				adLogicZoneParams.getMappedVertical(),
				'',
				adLogicZoneParams.getPageType()
			].join('/');

		win.rubicontag.cmd.push(function () {
			var slot = win.rubicontag.defineSlot(
				'wikia_gpt' + slotPath + '/gpt/' + slotName,
				[[970, 250], [728, 90]], 'fastlane-mas-57').setPosition('atf');
			win.rubicontag.run(function(){
				console.log("Aaaaaaaa RUBICON");
			},{
				slots:[slot]
			});

		});

	}

	function wasCalled() {
		log(['wasCalled', called], 'debug', logGroup);
		return called;
	}

	function getSlotParams(slotName) {
		log(['getSlotParams', slotName], 'debug', logGroup);
		var dfpParams = {},
			slotSize,
			dfpKey,
			price;

		if (oxResponse && slots[slotName]) {
			slotSize = slots[slotName];
			price = priceMap[slotName];
			if (price) {
				dfpKey = 'ox' + slotSize;
				dfpParams[dfpKey] = price;

				log(['getSlotParams', dfpKey, price], 'debug', logGroup);
				return dfpParams;
			}
		}
		log(['getSlotParams - no price since ad has been already displayed', slotName], 'debug', logGroup);
		return {};
	}

	return {
		call: call,
		getSlotParams: getSlotParams,
		trackState: trackState,
		wasCalled: wasCalled
	};
});
