<?php

require_once( dirname( __FILE__ ) . '../../../Maintenance.php' );

class BrokenIAllInfoboxesCheck extends Maintenance {

	/**
	 * Set script options
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'BrokenIAllInfoboxesCheck';
	}

	public function execute() {
		global $wgServer;

		$dbr = wfGetDB( DB_SLAVE );

		$infoboxes = ( new WikiaSQL() )->SELECT( 'qc_value', 'qc_namespace', 'qc_title' )->FROM( 'querycache' )->WHERE( 'qc_type' )->EQUAL_TO( AllinfoboxesQueryPage::ALL_INFOBOXES_TYPE )->run( $dbr, function ( ResultWrapper $result ) {
				$out = [ ];
				while ( $row = $result->fetchRow() ) {
					$out[] = [ 'pageid' => $row['qc_value'], 'title' => $row['qc_title'], 'ns' => $row['qc_namespace'] ];
				}

				return $out;
			} );

		$infoboxTags = $this->getInfoboxTags();

		$diff = count($infoboxes) - $infoboxTags;
		if ($diff==0) {
			$sign = '0 ';
		} elseif ($diff<0) {
			$sign = '- ';
		} else {
			$sign = '+ ';
		}
		echo $sign . $wgServer . ': '. count($infoboxes) . ' / ' . $infoboxTags . PHP_EOL;
	}

	private function getInfoboxTags() {
		global $wgStatsDB, $wgCityId;

		$dbs = wfGetDB( DB_SLAVE, [], $wgStatsDB );
		$res = $dbs->select(
			'city_used_tags',
			[ 'ct_kind as tag', 'count(*) as cnt' ],
			[
				'ct_wikia_id' => $wgCityId,
				'ct_kind' => 'infobox'
			],
			__METHOD__,
			[
				'GROUP BY' => 'ct_kind',
				'ORDER BY' => 'ct_kind'
			]
		);

		$tags = [];
		foreach ( $res as $row ) {
			$tags[ $row->tag ] = intval( $row->cnt );
		}
		return !empty($tags['infobox'])?$tags['infobox']:0;
	}
}

$maintClass = 'BrokenIAllInfoboxesCheck';
require_once( RUN_MAINTENANCE_IF_MAIN );
