define('ext.wikia.articleSnippet.popover',
	['jquery', 'wikia.nirvana', 'wikia.window', 'wikia.loader', 'wikia.mustache', 'wikia.cache'],
	function ($, nirvana, w, loader, mustache, cache) {
		'use strict';

		var $target,
			linkContent,
			snippetId,
			$snippetElement,
			templateCacheKey = 'articleSnippetTemplate',
			timeout;

		function init() {
			$('#WikiaArticle').on({
				mouseenter: getArticleSnippet,
				mouseleave: resetLoader
			}, 'a.mw-redirect, a:not([class])');
		}

		function getArticleSnippet(e) {
			$target = $(e.currentTarget);
			$target.attr('title', '');
			linkContent = $target.html();
			snippetId = 'ArticleSnippet' + stripLinkContent(linkContent);
			$snippetElement = $('#' + snippetId);

			if ($snippetElement.length === 0) {
				if (!$(linkContent).is('*') && !$target.data('no-snippet')) {
					timeout = setTimeout(function () {
						showLoader(e);
						timeout = setTimeout(function() {
							nirvana.getJson(
								'ArticleSnippetApi',
								'getArticleSnippet',
								{
									'pageTitle': encodeURIComponent(linkContent),
								},
								function (response) {
									if (processArticleSnippet(response)) {
										showArticleSnippet(e);
									} else {
										resetLoader(e);
									}
								}
							);
						}, 500);
					}, 500);
				}
			} else {
				showArticleSnippet(e);
			}
		}

		function showArticleSnippet(e) {
			moveArticleSnippet(e);
			$snippetElement.show();
			bindSnippetActionsToTarget($target);
			resetLoader();
		}

		function processArticleSnippet(data) {
			var snippetData;

			if ($.isPlainObject(data.articleSnippet)) {
				snippetData = data.articleSnippet;
				createSnippetElement(snippetData);
				return true;
			} else {
				$target.data('no-snippet', 'true');
				return false;
			}
		}

		function bindSnippetActionsToTarget($target) {
			$target.on({
				mousemove: moveArticleSnippet,
				mouseleave: hideArticleSnippet
			});
		}

		function moveArticleSnippet(e) {
			var x = e.clientX,
				y = e.clientY;
			$snippetElement
				.css('top', (y + 20) + 'px')
				.css('left', (x + 20) + 'px');
		}

		function hideArticleSnippet(e) {
			$snippetElement.hide();
		}

		function createSnippetElement(snippetData) {
			var template = cache.get(templateCacheKey);

			if (!template) {
				$.when(
					loader({
						type: loader.MULTI,
						resources: {
							mustache: '//extensions/wikia/ArticleSnippet/templates/ArticleSnippetPopover.mustache'
						}
					})
				).done(function (resources) {
					var template = resources.mustache[0];
					cache.set(templateCacheKey, template, cache.CACHE_STANDARD);
					renderTemplate(template, snippetData);
				});
			} else {
				renderTemplate(template, snippetData);
			}
		}

		function renderTemplate(template, snippetData) {
			if ( $snippetElement.length === 0 ) {
				$snippetElement = $(mustache.render(template, {
					snippetId: snippetId,
					title: snippetData.title,
					imageUrl: snippetData.image,
					highlights: snippetData.highlights
				})).appendTo('#WikiaArticle');
			}
		}

		function showLoader(e) {
			$target.css('cursor', 'progress');
		}

		function resetLoader(e) {
			clearTimeout(timeout);
			$target.css('cursor', 'pointer');
		}

		function stripLinkContent(linkContent) {
			return linkContent.replace(/\s/g, '');
		}

		return {
			init: init
		}
	}
);
