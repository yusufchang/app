/*global define*/
/*jslint nomen: true*/
/*jshint camelcase: false*/
define('ext.wikia.adEngine.provider.outbrain', [
	'wikia.log',
	'wikia.window',
	'wikia.document',
	'ext.wikia.adEngine.adContext',
	'ext.wikia.adEngine.slotTweaker'
], function (log, window, document, adContext, slotTweaker) {
	'use strict';

	var logGroup = 'ext.wikia.adEngine.provider.outbrain',
		outbrainSlotname = 'NATIVE_OUTBRAIN',
		libraryLoaded = false,
		readMoreDiv = document.getElementById('RelatedPagesModuleWrapper'),
		mercuryReadMoreDiv = document.getElementsByClassName('content-recommendations'),
		context = adContext.getContext(),
		isMobile = context.targeting.skin === 'wikiamobile' || context.targeting.skin === 'mercury';

	function canHandleSlot(slot) {
		log(['canHandleSlot', slot], 'debug', logGroup);

		if (slot !== outbrainSlotname) {
			log(['canHandleSlot', slot, 'Wrong slot name, disabling'], 'error', logGroup);
			return false;
		}

		if (!readMoreDiv && !mercuryReadMoreDiv) {
			log(['canHandleSlot', slot, 'No "read more" section, disabling'], 'error', logGroup);
			return false;
		}

		return true;
	}

	function loadOutbrain() {
		var div, s;

		if (libraryLoaded) {
			log(['loadOutbrain', 'Already loaded. Skipping.'], 'debug', logGroup);
			return;
		}

		s = document.createElement('script');
		s.async = true;
		s.src = 'http://widgets.outbrain.com/outbrain.js';
		s.id = logGroup;

		div = document.createElement('div');
		div.className = 'OUTBRAIN';
		div.dataset.src = document.querySelector('meta[property="og:url"]').getAttribute('content');
		div.dataset.widgetId = isMobile ? 'MB_1' : 'AR_1';
		div.dataset.obTemplate = 'Wikia';

		document.getElementById(outbrainSlotname).appendChild(div);
		document.getElementById(outbrainSlotname).appendChild(s);

		libraryLoaded = true;
		log(['loadOutbrain', 'Loaded.'], 'debug', logGroup);
	}

	function fillInSlot(slotname, success) {
		log(['fillInSlot', slotname], 'debug', logGroup);
		loadOutbrain();
		slotTweaker.show(slotname);
		success();
	}

	return {
		name: 'Outbrain',
		canHandleSlot: canHandleSlot,
		fillInSlot: fillInSlot
	};

});
