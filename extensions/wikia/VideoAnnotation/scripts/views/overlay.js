define('annotations.views.overlay', [], function () {
	'use strict';

	function Overlay(options) {
		this.$el = options.$el;
		this.render();
	}

	Overlay.prototype.render = function () {
		var $overlay = $('<div class="overlay"></div>');
		this.$el.addClass('video-annotations-overlay-wrapper');
		this.$el.append($overlay);
	};

	return Overlay;
});