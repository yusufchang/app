<?php
/**
 * Created by adam
 * Date: 07.08.13
 */

require_once( dirname(__FILE__) . '/../Maintenance.php' );

class TopModuleInvalidate extends Maintenance {

	public function execute() {
		global $wgMemc;
		if ( !empty( $_ENV['SERVER_ID'] ) && $wgMemc ) {
			$cacheKey = wfMemcKey( 'WikiaSearchController', 'WikiaSearch', 'topWikiArticles', $_ENV['SERVER_ID'] );
			$wgMemc->set( $cacheKey, null, 1 );
		}
	}
}

$maintClass = "TopModuleInvalidate";
require_once( DO_MAINTENANCE );