<?php
/**
 * Created by adam
 * Date: 20.12.13
 */

class InfoboxService extends Service {

	/**
	 * @var DatabaseBase $db
	 */
	protected $db;

	public function __construct() {
		$this->db = $this->initConnection();
	}

	protected function initConnection() {
		return wfGetDB(DB_MASTER, array(), F::app()->wg->ExternalDatawareDB);
	}

	public function getValuesForTitle( $title ) {
		$result = [];
		$res = $this->db->select(
			'info_data',
			'*',
			"title = " . $this->db->addQuotes( $title )
		);
		while( $row = $res->fetchRow() ) {
			$result[] =
				[
					'key' => $row['info_key'],
					'value' => $row['value'],
					'template' => $row['template'],
					'additional' => $row['additional']
				];
		}
		return $result;
	}

	public function getKeys() {
		$result = [];
		$res = $this->db->select(
			'info_data',
			'count(*) as num, info_key',
			'',
			'',
			[ 'GROUP BY' => 'info_key', 'ORDER BY' => 'num DESC' ]
		);
		while( $row = $res->fetchRow() ) {
			$result[] = [ 'count' => $row['num'], 'key' => $row['info_key'] ];
		}
		return $result;
	}

	public function getValuesForKey( $key, $limit = 10 ) {
		$result = [];
		$res = $this->db->select(
			'info_data',
			'value, additional, template, title',
			'info_key = ' . $this->db->addQuotes( $key ),
			'',
			[ 'LIMIT' => $limit, 'GROUP BY' => 'wid' ]
		);
		while( $row = $res->fetchRow() ) {
			$result[] = [
				'value' => $row['value'],
				'additional' => $row['additional'],
				'template' => $row['template'],
				'title' => $row['title']
			];
		}
		return $result;
	}
}