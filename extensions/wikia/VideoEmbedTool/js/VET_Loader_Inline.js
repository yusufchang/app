(function (window, $) {
	'use strict';

	if (window.vetLoaderInline) {
		return;
	}

	var vetLoaderInline = {};

	/**
	 * Load template, js, scss, and messages. Only called the first time VET is opened.
	 * @returns {Array}
	 */
	function loadResources() {
		var deferredList = [],
			$pageTitle = $("h1.pageTitle").html(),
			$videoSuggestionSection = $('#VideoSuggestionSection');

		if (!$videoSuggestionSection) {
			return deferredList;
		}

		// Get modal template HTML
		// Keep this deferred first because the output is used in the promise
		deferredList.push(
			$.nirvana.sendRequest({
				controller: 'VideoEmbedToolController',
				method: 'search',
				type: 'get',
				format: 'html',
				data: {
					pageTitle: $pageTitle,
					svSize: 3
				},
				callback: function(res) {
					if (res) {
						$('#VideoSuggestionSection').html(res);
					}
				}
			})
		);


		// Get messages
		deferredList.push(
			$.getMessages('VideoEmbedTool')
		);

		return deferredList;
	}

	/*
	 * @param {Object} options Control options sent to VET from extensions
	 * @param {jQuery} $elem Element that was clicked on to open the VET modal
	 */
	vetLoaderInline.load = function (options, $elem) {
		$.getResources([
			$.getAssetManagerGroupUrl('VET_js', {}),
			$.getSassCommonURL('/extensions/wikia/VideoEmbedTool/css/VET.scss'),
			$.getSassCommonURL('/extensions/wikia/WikiaStyleGuide/css/Dropdown.scss')
		]);

		loadResources();

		if (window.wgUserName === null && window.wgAction === 'edit') {
			// handle login on edit page
			window.UserLogin.rteForceLogin();
		} else if (window.wgUserName === null) {
			// handle login on article page
			window.UserLoginModal.show({
				origin: 'vet',
				callback: function () {
					window.UserLogin.forceLoggedIn = true;
					vetLoaderInline.load(options);
				}
			});
		}
	};

	/* Extends jQuery to make any element an add video button
	 *
	 * @param object options - options to be passed to vetLoader.load(). See above for example.
	 */
	$.fn.addVideoButton = function (options) {
		return this.each(function () {
			$( function () {
				var $this = $(this);
				vetLoaderInline.load(options, $this);
			});
		});
	};

	window.vetLoaderInline = vetLoaderInline;

})(window, jQuery);
