<?php

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

class DeletedArticlesSolrSync extends Maintenance {

	public function execute() {
		if ( !isset( $_ENV[ 'SERVER_ID' ] ) ) {
			die( 1 );
		}
		$ids = ( new WikiaSQL() )->SELECT()->DISTINCT( 'ar_page_id' )->FROM( 'archive' )->run( wfGetDB( DB_SLAVE ), function ( $data ) {
			$wid = $_ENV[ 'SERVER_ID' ];
			$it = 0;
			$items = [ ];
			while ( $row = $data->fetchRow() ) {
				if ( $it >= 1000 ) {
					//send current data and reset
					$this->send( $items );
					$items = [ ];
				}
				if ( !empty( $row[ 'ar_page_id' ] ) ) {
					$it += 1;
					$item = new stdClass();
					$item->id = $wid . "_" . $row[ 'ar_page_id' ];
					$items[ ] = $item;
				}
			}
			//send last batch
			$this->send( $items );
		} );
	}

	private function send( $items ) {
		if ( !empty( $items ) ) {
			$batch = new stdClass();
			$batch->delete = $items;
			$json = json_encode( $batch );
			print_r($json);
			$this->execCurlWithData( $json );
		}
	}

	protected function getCurlConnection() {
		if ( !isset( $this->curlClient ) ) {
			$this->curlClient = curl_init( $this->getMasterSolrUrl() );
			curl_setopt( $this->curlClient, CURLOPT_HTTPHEADER, [ 'Content-type:application/json' ] );
		}
		return $this->curlClient;
	}

	protected function execCurlWithData( $json ) {
		curl_setopt( $this->getCurlConnection(), CURLOPT_POSTFIELDS, $json );
		$response = curl_exec( $this->curlClient );
		print_r($response);
		curl_close( $this->curlClient );
		//reset connection
		unset( $this->curlClient );
	}

	protected function getMasterSolrUrl() {
		return 'http://search-master:8983/solr/main/update';
	}
}

$maintClass = 'DeletedArticlesSolrSync';
require( RUN_MAINTENANCE_IF_MAIN );