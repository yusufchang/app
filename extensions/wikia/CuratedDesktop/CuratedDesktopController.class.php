<?php

class CuratedDesktopController extends WikiaController {

	function index() {
		$this->response->addAsset( 'extensions/wikia/CuratedDesktop/styles/slippry.scss' );
		$this->response->addAsset( 'extensions/wikia/CuratedDesktop/scripts/slippry.js' );
		$this->response->addAsset( 'extensions/wikia/CuratedDesktop/styles/CuratedDesktop.scss' );
		$this->response->addAsset( 'extensions/wikia/CuratedDesktop/scripts/CuratedDesktop.js' );

		list($featured, $curated, $optional) = $this->getCCData();

		$this->response->setVal('featured', $featured );
		$this->response->setVal('curated',  $curated );
		$this->response->setVal('optional', $optional );
		$this->response->setVal('wordmark', $this->getWordmark() );
	}

	private function getCCData() {
		$ccData = $this->sendRequest('CuratedContent', 'getData')->getData()['data'];
		$featured = $curated = $optional = [];

		foreach( $ccData as $entity ) {
			switch( $entity['section'] ) {
				case 'featured':
					$featured = $entity;
					break;
				case 'optional':
					$optional = $entity;
					break;
				case 'curated':
					$curated[] = $entity;
			}
		}

		return [$featured, $curated, $optional];
	}

	private function getWordmark() {
		return $this->app->sendRequest( 'WikiHeader', 'Wordmark' )->getData();
	}

	public static function getThumb( $imageUrl, $w, $h = null ) {
		// not working for non-vignette urls
		if ( empty( $h ) ) {
			return VignetteRequest::fromUrl( $imageUrl )->zoomCrop()->width( $w )->height( $w )->url();
		} else {
			return VignetteRequest::fromUrl( $imageUrl )->fixedAspectRatio()->width( $w )->height( $h )->url();
		}
	}
}
