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

		$titles = [ Title::newFromText( 'Wikia.css', NS_MEDIAWIKI ), Title::newFromText( 'Common.css', NS_MEDIAWIKI ), Title::newFromText( 'Monobook.css', NS_MEDIAWIKI ) ];

		/* @var $title Title */
		foreach ( $titles as $title ) {
			$subpages = $title->getSubpages();

			foreach ( $subpages as $subpage ) {
				$this->processArticleFromTitle( $subpage );
			}

			$this->processArticleFromTitle( $title );
		}
	}

	private function processArticleFromTitle( $title ) {
		global $wgServer;

		$article = Article::newFromTitle( $title, RequestContext::getMain() );
		if ( $article ) {
			$content = $article->getContent();
			preg_match( '/\.portable\-infobox/', '', $matches );
			if ( $matches ) {
				echo sprintf( "%20s,%20s", $wgServer, $title->getText() );
			}
		}
	}

}

$maintClass = 'InfoboxStylingDetector';
require_once( RUN_MAINTENANCE_IF_MAIN );
