/*exported AdDecoratorPageDimensions*/
var AdDecoratorTriggerExtraMedrec = function (log, document) {
	'use strict';

	var logGroup = 'ext.wikia.adengine.decorator.triggerextramedrec',
		triggerSlotname = 'CORP_TOP_RIGHT_BOXAD_TRIGGER',
		realSlotname = 'CORP_TOP_RIGHT_BOXAD',
		realSlotMarkup = '<div class="home-top-right-ads"><div id="' + realSlotname + '" class="wikia-ad"></div></div>',
		rightColumns = document.getElementsByClassName('main-page-tag-rcs'),
		rightColumnPresent = rightColumns.length === 1,
		rightColumn = rightColumnPresent && rightColumns[0],
		regularMedrecPresent = !!document.getElementById(realSlotname);

	/**
	 * fillInSlot decorator. Returns function to call instead.
	 *
	 * @returns {function}
	 */
	function decorator(fillInSlot) {
		log(['decorator', fillInSlot], 'debug', logGroup);

		return function (slot) {
			log(['decorated start', slot], 'debug', logGroup);

			var slotname = slot[0];

			if (slotname === triggerSlotname) {
				if (!regularMedrecPresent && rightColumnPresent) {
					log(['Adding extra medrec'], 'info', logGroup);
					rightColumn.insertAdjacentHTML('afterbegin', realSlotMarkup);
					slot[0] = realSlotname;
				}
			}

			log(['decorated params', slot], 'debug', logGroup);
			return fillInSlot(slot);
		};
	}

	return decorator;
};
