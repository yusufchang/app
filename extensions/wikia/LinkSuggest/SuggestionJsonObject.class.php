<?php
/**
 * @author: Jacek Jursza <jacek@wikia-inc.com>
 * Date: 08.08.13 12:21
 *
 */

class SuggestionJsonObject {

	protected $db = null;
	protected $app = null;
	protected $totalSuggestionLimit = 1000; // 1000 is currently max in querycache table

	public function __construct() {
		$this->db = wfGetDB( DB_SLAVE );
		$this->app = F::app();
	}

	/**
	 * @param int $totalSuggestionLimit
	 */
	public function setTotalSuggestionLimit( $totalSuggestionLimit ) {
		$this->totalSuggestionLimit = $totalSuggestionLimit;
	}

	/**
	 * @return int
	 */
	public function getTotalSuggestionLimit() {
		return $this->totalSuggestionLimit;
	}

	public function getWholeJson() {

		$res = $this->db->select(
			array( 'querycache' ),
			array( 'qc_namespace', 'qc_title' ),
			array(
				'qc_type' => 'Mostlinked',
				'qc_namespace' => $this->app->wg->contentNamespaces
			),
			__METHOD__,
			array( 'ORDER BY' => 'qc_value DESC', 'LIMIT' => $this->totalSuggestionLimit  )
		);

		$json = array();
		$json['titles'] = array();
		$json['basepath'] = $this->app->wg->ArticlePath;

		while ( $obj = $res->fetchObject() ) {
			$json['titles'][] = Title::newFromText( $obj->qc_title, $obj->qc_namespace)->getPrefixedText();
		}

		return json_encode( $json );
	}

}