define('annotaions.controllers.index', [
	'annotations.views.overlay',
    'annotations.views.form'
], function (Overlay, Form) {
	'use strict';

	function Annotations(options) {
		var $videoWrapper = options.$videoWrapper,
			$formWrapper = options.$formWrapper;

		this.overlay = new Overlay({
			$el: $videoWrapper
		});

		this.form = new Form({
			$el: $formWrapper
		});

	}

	return Annotations;
});