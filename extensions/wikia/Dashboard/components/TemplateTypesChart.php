<?php

namespace Wikia\Dashboard\Components;

class TemplateTypesChart {
	const CLASSNAME = 'template-types-chart';
	private static $instance = null;

	private function __construct() {
	}

	/**
	 * @return null|TemplateTypesChart
	 */
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Returns template types for current wiki with their counts
	 * @return array
	 */
	public function getTemplateTypesWithCounts() {
		global $wgCityId;
		$db = wfGetDB( DB_SLAVE, [], 'templateclassification' );
		$types = [];

		$results = ( new \WikiaSQL() )
			->SELECT( 'count(*)' )->AS_( 'templatecount' )
			->SELECT( 'type' )
			->FROM( 'raw_types' )
			->WHERE( 'provider' )->EQUAL_TO( 'user' )
			->AND_('wiki_id')->EQUAL_TO( $wgCityId )
			->GROUP_BY( 'type' )
			->runLoop( $db, function( &$types, $row ) {
				$types []= [
					'type' => $row->type,
					'count' => $row->templatecount
				];
			});

		return $results;
	}

}
