define('ext.wikia.articleSnippet.popover',
	['jquery', 'wikia.nirvana'],
	function ($, nirvana) {
		'use strict';

		function init() {
			$('#WikiaArticle').on('mouseover', 'a:not(.new)', getArticleSnippet)
		}

		function processArticleSnippet(data) {
			var snippet;

			if (data.articleSnippetData.length > 0) {
				snippet = data.articleSnippetData[0].data;


			}
		}

		function getArticleSnippet(e) {
			var $target = $(e.currentTarget),
				html = $target.html();

			if ( !$(html).is('*') ) {
				nirvana.getJson(
					'ArticleSnippetApi',
					'getArticleSnippet',
					{
						'pageTitle': encodeURIComponent(html),
					},
					processArticleSnippet
				);
			}
		}

		return {
			init: init
		}
	}
);
