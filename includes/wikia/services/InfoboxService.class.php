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
			$result[] = $row;
		}
		return $result;
	}
}