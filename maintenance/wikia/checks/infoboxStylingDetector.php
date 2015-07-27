<?php

/**
 * Script that checks in MediaWiki:Wikia.css, MediaWiki:Common.css, MediaWiki:Monobook.css
 * the styles for portable infoboxes on a wiki
 *
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '../../../Maintenance.php' );

class InfoboxStylingDetector extends Maintenance {

	/**
	 * Set script options
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Portable Infobox Styling detector';
	}

	public function execute() {
		global $wgCityId, $wgSitename, $wgServer;

		$stylesheets = [
			'Wikia.css',
			'Common.css',
			'Monobook.css'
		];

		foreach ( $stylesheets as $stylesheet ) {
			$title = Title::newFromText( $stylesheet, NS_MEDIAWIKI );

			$this->processArticleFromTitle( $title );

			$subpages = $title->getSubpages();
			foreach ( $subpages as $subpage ) {
				$this->processArticleFromTitle( $subpage );
			}

		}
	}

	private function processArticleFromTitle( $title ) {
		global $wgServer;

		$article = Article::newFromTitle( $title, RequestContext::getMain() );
		if ( $article ) {
			$content = $article->getContent();
			preg_match_all( '/\.portable\-infobox/', $content, $matches );
			if ( !empty( $matches[0] ) ) {
				echo sprintf( "%-90s, Lines: %10s\n", $title->getFullUrl(), count( $matches[0] ) );
			}
		}
	}

}

$maintClass = 'InfoboxStylingDetector';
require_once( RUN_MAINTENANCE_IF_MAIN );
