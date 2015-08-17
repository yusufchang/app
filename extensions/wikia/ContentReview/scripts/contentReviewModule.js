define(
	'ext.wikia.contentReview.module',
	['jquery', 'mw', 'wikia.loader', 'wikia.nirvana', 'wikia.window', 'BannerNotification'],
	function($, mw, loader, nirvana, win, BannerNotification) {
		'use strict';

		function init() {
			$.when(loader({
				type: loader.MULTI,
				resources: {
					messages: 'ContentReviewModule'
				}
			})).done(function (res) {
				mw.messages.set(res.messages);
				bindEvents();
			});
		}

		function bindEvents() {
			$('#content-review-module-submit').on('click', submitPageForReview);
		}

		function submitPageForReview(event) {
			event.preventDefault();

			var moduleType = $(this).data('type'),
				data = {
				pageId: mw.config.get('wgArticleId'),
				wikiId: mw.config.get('wgCityId'),
				editToken: mw.user.tokens.get('editToken')
			};

			nirvana.sendRequest({
				controller: 'ContentReviewApiController',
				method: 'submitPageForReview',
				data: data,
				callback: function (response) {
					var notification;

					if (response.status) {
						notification = new BannerNotification(
							/**
							 * The following message keys may be generated:
							 * content-review-module-submit-success-insert
							 * content-review-module-submit-success-update
							 */
							mw.message('content-review-module-submit-success-' + moduleType).escaped(),
							'confirm'
						);

						$('.content-review-module').hide();
					} else if ( response.exception.length > 0 ) {
						notification = new BannerNotification(
							mw.message('content-review-module-submit-exception', response.exception).escaped(),
							'error'
						);
					} else {
						notification = new BannerNotification(
							mw.message('content-review-module-submit-error').escaped(),
							'error'
						);
					}

					notification.show()
				}
			});
		}

		return {
			init: init
		};
	}
);
