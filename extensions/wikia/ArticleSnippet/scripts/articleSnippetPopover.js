define('ext.wikia.articleSnippet.popover',
	['jquery', 'wikia.nirvana'],
	function ($, nirvana) {
		'use strict';

		function init() {
			$('#WikiaArticle').on('mouseover', 'a:not(.new)', getArticleSnippet)
		}

		function getArticleSnippet(e) {
			var $target = $(e.currentTarget);

			nirvana.getJson(
				'ArticleSnippetApi',
				'getArticleSnippet',
				{
					'title' : $target.html(),
				}
			);
		}

		return {
			init: init
		}
	}
);
