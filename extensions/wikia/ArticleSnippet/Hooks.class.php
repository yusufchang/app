<?php

namespace Wikia\ArticleSnippet;

class Hooks extends \ContextSource {

	public static function register() {
		$hooks = new self();
		\Hooks::register( 'BeforePageDisplay', [ $hooks, 'onBeforePageDisplay' ] );
	}

	/**
	 * @param \OutputPage $outputPage
	 * @param \Skin $skin
	 * @return bool
	 */
	public function onBeforePageDisplay( \OutputPage $outputPage, \Skin $skin ) {
		\Wikia::addAssetsToOutput( 'article_snippet_popover_js' );
		\Wikia::addAssetsToOutput( 'article_snippet_popover_scss' );
		return true;
	}
}
