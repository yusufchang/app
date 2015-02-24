/*global define,setTimeout*/
/*jshint maxlen:125, camelcase:false, maxdepth:7*/
define('ext.wikia.adEngine.gptHelper', [
	'wikia.log',
	'wikia.window',
	'wikia.document',
	'ext.wikia.adEngine.adLogicPageParams',
	'ext.wikia.adEngine.slotTweaker',
	'ext.wikia.adEngine.wikiaGptAdDetect'
], function (log, window, document, adLogicPageParams, slotTweaker, gptAdDetect) {
	'use strict';

	var logGroup = 'ext.wikia.adEngine.wikiaGptHelper',
		gptLoaded = false,
		slotQueue = [],
		gptSlots = {},
		gptCallbacks = {},
		googletag,
		pubads,
		pageLevelParams,
		fallbackSize = [1, 1]; // Size to return if there are no sizes matching the screen dimensions

	function convertSizesToGpt(slotsize) {
		log(['convertSizeToGpt', slotsize], 'debug', logGroup);
		var tmp1 = slotsize.split(','),
			sizes = [],
			tmp2,
			i;

		for (i = 0; i < tmp1.length; i += 1) {
			tmp2 = tmp1[i].split('x');
			sizes.push([parseInt(tmp2[0], 10), parseInt(tmp2[1], 10)]);
		}

		return sizes;
	}

	function filterOutSizesBiggerThanScreenSize(sizes) {
		log(['filterOutSizesBiggerThanScreenSize', sizes], 'debug', logGroup);
		var goodSizes = [], i, len, minWidth;

		minWidth = document.documentElement.offsetWidth;

		for (i = 0, len = sizes.length; i < len; i += 1) {
			if (sizes[i][0] <= minWidth) {
				goodSizes.push(sizes[i]);
			}
		}

		if (goodSizes.length === 0) {
			log(['filterOutSizesBiggerThanScreenSize', 'No sizes left. Returning fallbackSize only'], 'error', logGroup);
			goodSizes.push(fallbackSize);
		}

		log(['filterOutSizesBiggerThanScreenSize', 'result', goodSizes], 'debug', logGroup);
		return goodSizes;
	}

	function setPageLevelParams() {
		var name,
			value;

		pageLevelParams = adLogicPageParams.getPageLevelParams();

		log(['setPageLevelParams', pageLevelParams], 'debug', logGroup);

		for (name in pageLevelParams) {
			if (pageLevelParams.hasOwnProperty(name)) {
				value = pageLevelParams[name];
				if (value) {
					log(['setPageLevelParams', 'pubads.setTargeting', name, value], 'debug', logGroup);
					pubads.setTargeting(name, value);
				}
			}
		}
	}

	function registerGptCallback(adDivId, gptCallback) {
		log(['registerGptCallback', adDivId], 'info', logGroup);
		gptCallbacks[adDivId] = gptCallback;
	}

	function dispatchGptEvent(event) {
		var adDivId;

		log(['dispatchGptEvent', event], 'info', logGroup);

		for (adDivId in gptCallbacks) {
			if (gptCallbacks.hasOwnProperty(adDivId)) {
				if (gptCallbacks[adDivId] && event.slot && event.slot === gptSlots[adDivId]) {
					log(['dispatchGptEvent', event, 'Launching registered callback'], 'debug', logGroup);
					gptCallbacks[adDivId](event);
					return;
				}
			}
		}

		log(['dispatchGptEvent', event, 'No callback registered for this slot render ended event'], 'error', logGroup);
	}

	function loadGptOnce() {
		if (!gptLoaded) {
			log('loadGpt', 'debug', logGroup);

			var gads = document.createElement('script'),
				node = document.getElementsByTagName('script')[0];

			gptLoaded = true;

			window.googletag = window.googletag || {};
			window.googletag.cmd = window.googletag.cmd || [];

			gads.async = true;
			gads.type = 'text/javascript';
			gads.src = '//www.googletagservices.com/tag/js/gpt.js';

			log('Appending GPT script to head', 'debug', logGroup);

			node.parentNode.insertBefore(gads, node);
			googletag = window.googletag;

			// Enable services
			log(['loadGpt', 'googletag.cmd.push'], 'info', logGroup);
			googletag.cmd.push(function () {
				pubads = googletag.pubads();

				setPageLevelParams();

				pubads.collapseEmptyDivs();
				pubads.enableSingleRequest();
				pubads.disableInitialLoad(); // manually request ads using refresh
				pubads.addEventListener('slotRenderEnded', dispatchGptEvent);

				googletag.enableServices();

				log(['loadGpt', 'googletag.cmd.push', 'done'], 'debug', logGroup);
			});
		}
	}

	function pushAd(slotName, slotPath, slotTargeting, success, error) {
		var slotDiv, // set in queueAd
			adDivId = 'wikia_gpt_helper' + slotPath;

		function callSuccess(adInfo) {
			if (typeof success === 'function') {
				success(adInfo);
			}
		}

		function callError(adInfo) {
			slotTweaker.hide(adDivId);
			if (typeof error === 'function') {
				adInfo = adInfo || {};
				adInfo.method = 'hop';
				error(adInfo);
			}
		}

		function queueAd() {
			var name, value, sizes, slot;

			slotDiv = document.getElementById(adDivId);

			if (!slotDiv) {
				// Create a div for the GPT ad
				slotDiv = document.createElement('div');
				slotDiv.id = adDivId;
				document.getElementById(slotName).appendChild(slotDiv);

				sizes = convertSizesToGpt(slotTargeting.size);

				if (slotName.match(/TOP_LEADERBOARD/)) {
					sizes = filterOutSizesBiggerThanScreenSize(sizes);
				}

				log(['defineSlot', 'googletag.defineSlot', slotPath, sizes, adDivId], 'debug', logGroup);
				slot = googletag.defineSlot(slotPath, sizes, adDivId);
				slot.addService(pubads);

				delete slotTargeting.size;

				for (name in slotTargeting) {
					if (slotTargeting.hasOwnProperty(name)) {
						value = slotTargeting[name];
						if (value) {
							log(['defineSlot', 'slot.setTargeting', name, value], 'debug', logGroup);
							slot.setTargeting(name, value);
						}
					}
				}

				gptSlots[adDivId] = slot;

				// Display div through GPT
				log(['googletag.display', adDivId], 'debug', logGroup);
				googletag.display(adDivId);

				// Save page level and slot level params for easier ad delivery debugging
				slotDiv.setAttribute('data-gpt-slot-sizes', JSON.stringify(sizes));
				slotDiv.setAttribute('data-gpt-slot-params', JSON.stringify(slotTargeting));
				slotDiv.setAttribute('data-gpt-page-params', JSON.stringify(pageLevelParams));
			}

			// Some broken ads never fire "success" event, so we show the div now (and maybe hide later)
			slotTweaker.show(adDivId);
			slotQueue.push(gptSlots[adDivId]);
		}

		function gptCallback(event) {
			log(['gptCallback', adDivId, event], 'info', logGroup);

			// Add debug info
			slotDiv.setAttribute('data-gpt-line-item-id', JSON.stringify(event.lineItemId));
			slotDiv.setAttribute('data-gpt-creative-id', JSON.stringify(event.creativeId));
			slotDiv.setAttribute('data-gpt-creative-size', JSON.stringify(event.size));

			var iframe = slotDiv.querySelector('div[id*="_container_"] iframe');

			// IE doesn't allow us to inspect GPT iframe at this point.
			// Let's launch our callback in a setTimeout instead.
			setTimeout(function () {
				gptAdDetect.onAdLoad(adDivId, event, iframe, callSuccess, callError);
			}, 0);
		}

		log(['pushAd', slotName], 'info', logGroup);

		loadGptOnce();
		registerGptCallback(adDivId, gptCallback);
		googletag.cmd.push(queueAd);
	}

	function flushAds() {
		if (!gptLoaded) {
			log(['flushAds', 'done', 'no slots to flush'], 'info', logGroup);
			return;
		}

		googletag.cmd.push(function () {
			log(['flushAds', 'start'], 'info', logGroup);

			log(['flushAds', 'refresh', slotQueue], 'debug', logGroup);

			if (slotQueue.length) {
				googletag.pubads().refresh(slotQueue);
				slotQueue = [];
			}

			log(['flushAds', 'done'], 'info', logGroup);
		});
	}

	return {
		pushAd: pushAd,
		flushAds: flushAds
	};

});
