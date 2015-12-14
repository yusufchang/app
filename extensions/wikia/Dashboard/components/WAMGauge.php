<?php

namespace Wikia\Dashboard\Components;

class WAMGauge {
	const CLASSNAME = 'WAM-gauge';
	private static $instance = null;

	private function __construct() {
	}

	/**
	 * @return null|\Wikia\Dashboard\Components\WAMGauge
	 */
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Returns WAM score for current wiki
	 * @return float
	 */
	public function getWAMScore() {
		global $wgCityId;

		$result = ( new \WAMService() )->getCurrentWamScoreForWiki( $wgCityId );

		return $result;
	}

}
