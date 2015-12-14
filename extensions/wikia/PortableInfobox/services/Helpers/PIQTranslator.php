<?php

/**
 * Created by IntelliJ IDEA.
 * User: adamr
 * Date: 14/12/15
 * Time: 13:21
 */
class PIQTranslator {

	public static function transform( $result ) {
		return new self( $result );
	}

	protected function __construct( $result ) {
		$this->data = $result;
	}

	public function toDataTable() {
		$keys = [ ];
		foreach ( $this->data as $d ) {
			$keys = array_unique( array_merge( $keys, array_keys( $d ) ) );
		}
		$filteredKeys = array_filter( $keys, function ( $key ) {
			if ( stripos( $key, "_" ) === 0 || in_array( $key, [ 'image', 'title' ] ) ) {
				return false;
			}

			return true;
		} );

		$out = [ ];
		foreach ( $this->data as $d ) {
			$out[] = [ 'title' => $d[ 'title' ], 'cols' =>
			// get data for keys
				array_map( function ( $key ) use ( $d ) {
					return !empty( $d[ $key ] ) ? $d[ $key ] : "";
				}, $filteredKeys )
			];
		}

		return [ 'keys' => $filteredKeys, 'data' => $out ];
	}
}
