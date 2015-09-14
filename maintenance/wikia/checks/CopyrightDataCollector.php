<?php

/**
 * Collect the Mediawiki:Copyright contents
 *
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '../../../Maintenance.php' );

class CopyrightDataCollectorScript extends Maintenance {

	/**
	 * Set script options
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Mediawiki:Copyright checker';
	}

	public function execute() {
		global $wgCityId;

		$title = Title::newFromText( 'Copyright', NS_MEDIAWIKI );

		echo $wgCityId
			. ': '
			. ( $title ? WikiPage::newFromID( $title->getArticleID() )->getText() : '*** EMPTY ***' )
			. PHP_EOL;
	}
}

$maintClass = 'CopyrightDataCollectorScript';
require_once( RUN_MAINTENANCE_IF_MAIN );
