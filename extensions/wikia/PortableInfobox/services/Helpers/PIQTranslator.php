<?php

/**
 * Created by IntelliJ IDEA.
 * User: adamr
 * Date: 14/12/15
 * Time: 13:21
 */
class PIQTranslator {
	const IMG_REGEX = '/^\/(?<bucket>[^\/]+)\/(images\/|avatars\/)?(?<relativePath>.*?)\/revision\/(?<revision>latest|\d+)(\/(?<thumbnailDefinition>.*))?/';
	private $imgSize = 176;

	public static function transform( $result ) {
		return new self( $result );
	}

	protected function __construct( $result ) {
		//filter out all no main ns articles from results
		$this->data = array_filter( $result, function ( $row ) {
			list( $wid, $pageid, $order ) = explode( '_', $row[ '_id' ] );
			$title = Title::newFromID( $pageid );

			return $title && $title->exists() && $title->inNamespace( NS_MAIN );
		} );
	}

	public function withImage( $size ) {
		$this->imgSize = $size;

		return $this;
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

	public function toList() {
		return array_map( function ( $row ) {
			list( $wid, $pageid, $order ) = explode( '_', $row[ '_id' ] );
			$title = Title::newFromID( $pageid );
			$url = $title && $title->exists() ? $title->getFullURL() : "";

			return [
				'title' => $row[ 'title' ],
				'url' => $url,
				'image' => $this->cropImage( $row[ 'image' ] )
			];
		}, $this->data );
	}

	public function toInfoboxList() {
		return array_map( function ( $row ) {
			list( $wid, $pageid, $order ) = explode( '_', $row[ '_id' ] );

			return PortableInfoboxDataService::newFromPageID( $pageid )->getData()[ $order ][ 'data' ];
		}, $this->data );
	}

	protected function cropImage( $imageUrl ) {
		preg_match( self::IMG_REGEX, parse_url( $imageUrl )[ 'path' ], $imgInfo );
		// make it vignette compatible
		$imgInfo[ 'relative-path' ] = $imgInfo[ 'relativePath' ];
		$thumb = VignetteRequest::fromConfigMap( $imgInfo );

		return $thumb ? $thumb->thumbnail()
			->width( $this->imgSize )->height( $this->imgSize )
			->topCropDown()->url() : "";
	}
}
