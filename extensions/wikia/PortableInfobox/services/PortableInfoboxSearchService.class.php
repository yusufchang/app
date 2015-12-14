<?php

use Elasticsearch\ClientBuilder;

class PortableInfoboxSearchService {

	public static function query( $query, $limit = 10 ) {
		global $wgCityId;

		$query = "{$query} AND _id:{$wgCityId}_*";
		$client = ClientBuilder::create()->setHosts( [ 'sony-datalog-s1:9200' ] )->build();
		$params = [
			'index' => 'pi-index',
			'type' => 'infobox',
			'size' => $limit,
			'body' => [
				'query' => [
					'query_string' => [
						'query' => $query
					]
				]
			]
		];
		$result = $client->search( $params );

		return !empty( $result[ 'hits' ][ 'hits' ] ) ? array_map( function ( $row ) {
			$result = $row[ '_source' ];
			$result[ '_id' ] = $row[ '_id' ];
			unset( $result[ '_from' ] );
			unset( $result[ 'data' ] );

			return $result;
		}, $result[ 'hits' ][ 'hits' ] ) : [ ];
	}

}
