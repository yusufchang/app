<?php
/**
 * Created by adam
 * Date: 07.03.14
 */

require_once( dirname( __FILE__ ) . '/../../Maintenance.php' );

class TvSolrIndexer extends Maintenance {

	protected $data = [];
	/**
	 * Do the actual work. All child classes will need to implement this
	 */
	public function execute() {

		$db = $this->getConnection();
		$res = $db->select( ['sw' => 'tv_series_wikis', 's' => 'tv_series'],
			'*',
			[ 'sw.series_lookup = s.series_lookup', 'wiki_lang = series_lang' ]
		);
//		series_name, series_lang, series_lookup, wiki_id, wiki_name, wiki_lang
		while( $row = $db->fetchRow( $res ) ) {
			$this->getFromRow( $row );
		}
		
		$queryData = $this->createSolrUpdate();
		$this->update( $queryData );
	}

	protected function getFromRow( $row ) {
		if ( !isset( $this->data[ $row['wiki_id'] ] ) ) {
			$this->data[ $row['wiki_id'] ] = [];
		}
		if ( !isset( $this->data[ $row['wiki_id' ] ][ $row['wiki_lang'] ] ) ) {
			$this->data[ $row['wiki_id' ] ][ $row['wiki_lang'] ] = [];
		}
		if ( !isset( $this->data[ $row['wiki_id' ] ][ 'txt' ] ) ) {
			$this->data[ $row['wiki_id' ] ][ 'txt' ] = [];
		}

		$this->data[ $row[ 'wiki_id' ] ][ $row[ 'wiki_lang' ] ][] = $row['series_name'];
		$this->data[ $row[ 'wiki_id' ] ][ 'txt' ][] = $row['series_name'];
	}

	protected function createSolrUpdate() {
		$result = [];
		foreach( $this->data as $id => $d ) {
			$wikiResult = [ 'id' => $id ];
			foreach( $d as $lang => $series ) {
				$name = ( $lang == 'txt' ) ? 'series_txt' : 'series_mv_'.$lang;
				$wikiResult[ $name ] = [ "set" => $series ];
			}
			$result[] = $wikiResult;
		}
		return $result;
	}

	protected function update( $data ) {
		$c = curl_init('http://dev-search-s4:8983/solr/xwiki/update');
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_HTTPHEADER, ['Content-type:application/json']);
		curl_setopt($c, CURLOPT_POSTFIELDS, json_encode( $data ) );
		curl_exec($c);
	}

	protected function getConnection() {
		global $wgExternalDatawareDB;
		if ( !isset( $this->db ) ) {
			$this->db = wfGetDB(DB_SLAVE, array(), $wgExternalDatawareDB);
		}
		return $this->db;
	}
}

$maintClass = 'TvSolrIndexer';
require( RUN_MAINTENANCE_IF_MAIN );