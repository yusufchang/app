define('ext.wikia.articleSnippet.popover',
	['jquery', 'wikia.nirvana', 'wikia.window'],
	function ($, nirvana, w) {
		'use strict';

		var $target,
			linkContent,
			snippetId,
			$snippetElement;

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
			$('#WikiaArticle').append( '<div id="' + snippetId + '" class="article-snippet"></div>' );
			$snippetElement = $('#' + snippetId);
			$snippetElement.append('<h3>' + snippetData.title + '</h3>');
			$snippetElement.append('<img src="' + snippetData.image + '">');
		}

		function stripLinkContent(linkContent) {
			return linkContent.replace(/\s/g, '');
		}

		return {
			init: init
		}
	}
);
