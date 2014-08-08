define('annotaions.controllers.index', [
	'annotations.views.button',
    'annotations.views.form'
], function (Button, Form) {
	'use strict';

	function Annotations(options) {
		var $videoWrapper = options.$videoWrapper,
			$formWrapper = options.$formWrapper;

		this.form = new Form({
			$el: $formWrapper
		});

		this.button = new Button({
			$el: $videoWrapper,
			form: this.form
		});

	}

	return Annotations;
});