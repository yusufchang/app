define('ext.wikia.articleSnippet.popover',
	['jquery', 'wikia.nirvana', 'wikia.window', 'wikia.loader', 'wikia.mustache', 'wikia.cache'],
	function ($, nirvana, w, loader, mustache, cache) {
		'use strict';

		var $target,
			linkContent,
			snippetId,
			$snippetElement,
			templateCacheKey = 'articleSnippetTemplate';

		function init() {
			$('#WikiaArticle').on('mouseenter', 'a:not(.new)', getArticleSnippet);
		}

		function getArticleSnippet(e) {
			$target = $(e.currentTarget);
			linkContent = $target.html();
			snippetId = 'ArticleSnippet' + stripLinkContent(linkContent);
			$snippetElement = $('#' + snippetId);

			if ($snippetElement.length === 0) {
				if (!$(linkContent).is('*')) {
					nirvana.getJson(
						'ArticleSnippetApi',
						'getArticleSnippet',
						{
							'pageTitle': encodeURIComponent(linkContent),
						},
						function (response) {
							$.when(
								processArticleSnippet(response)
							).done(showArticleSnippet(e));
						}
					);
				}
			} else {
				showArticleSnippet(e);
			}
		}

		function showArticleSnippet(e) {
			$target.attr('title', '');

			moveArticleSnippet(e);
			$snippetElement.show();
			bindSnippetActionsToTarget($target);
		}

		function processArticleSnippet(data) {
			var snippetData;

			if ($.isPlainObject(data.articleSnippet)) {
				snippetData = data.articleSnippet;
				createSnippetElement(snippetData);
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
			$snippetElement.css('top', (y + 20) + 'px');
			$snippetElement.css('left', (x + 20) + 'px');
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
			$('#WikiaArticle').append(mustache.render(template, {
				snippetId: snippetId,
				title: snippetData.title,
				imageUrl: snippetData.image,
				highlights: snippetData.highlights
			}));
			$snippetElement = $('#' + snippetId);
		}

		function stripLinkContent(linkContent) {
			return linkContent.replace(/\s/g, '');
		}

		return {
			init: init
		}
	}
);
