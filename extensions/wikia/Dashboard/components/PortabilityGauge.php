<?php

namespace Wikia\Dashboard\Components;

use Wikia\Util\GlobalStateWrapper;

class PortabilityGauge {
	const CLASSNAME = 'portability-gauge';
	private static $instance = null;

	private function __construct() {
	}

	/**
	 * @return null|PortabilityGauge
	 */
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function getPortabilityPercent() {
		global $wgPortableMetricDB;
		$db = wfGetDB( DB_SLAVE, [], $wgPortableMetricDB );

		//to jest pierwsze lepsze query - zmien na obliczanie portabilności z artykulwow
		//przeklasyfikowanych w przeciągu ostatnich 10dni wagowo po pageviewsach

		$result = ( new \WikiaSQL() )
			->SELECT( 'wiki_id', 'page_id' )
			->FROM( 'articlestats' )
			->WHERE( 'wiki_id' )->EQUAL_TO( '3035' )
			->runLoop( $db, function( &$flagsWithTypes, $row ) {
				var_dump($row);
			} );

		//tu skonczylam, dalej nie działa!
		var_dump($result);

		die;

		return $result;
	}

}