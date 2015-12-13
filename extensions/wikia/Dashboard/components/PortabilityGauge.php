<?php

namespace Wikia\Dashboard\Components;

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

	/**
	 * Returns portability percentage for current wiki
	 * @return float
	 */
	public function getPortabilityPercent() {
		global $wgCityId, $wgPortableMetricDB;
		$db = wfGetDB( DB_SLAVE, [], $wgPortableMetricDB );

		$result = ( new \WikiaSQL() )
			->SELECT( 'sum((portable_b||curatedcontent_b)*pageviews)/sum(pageviews)*100' )->AS_( 'portability' )
			->FROM( 'articlestats' )
			->JOIN( 'articledata' )
			->ON( 'articlestats.wiki_id', 'articledata.wiki_id' )
			->AND_( 'articlestats.page_id', 'articledata.page_id' )
			->WHERE( 'articledata.wiki_id' )->EQUAL_TO( $wgCityId )
			->run( $db, function( \ResultWrapper $queryResult ) {
				return $queryResult->fetchObject()->portability;
			}, 0);

		return (float)$result;
	}

}
