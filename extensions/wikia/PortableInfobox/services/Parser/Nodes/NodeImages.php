<?php
namespace Wikia\PortableInfobox\Parser\Nodes;

use Wikia\PortableInfobox\Helpers\ImageFilenameSanitizer;
use Wikia\PortableInfobox\Helpers\PortableInfoboxDataBag;
use WikiaFileHelper;

class NodeImages extends Node {

	const CAPTION_TAG_NAME = 'caption';

	public function getData() {
		if ( isset( $this->data ) ) {
			return $this->data;
		}
		$data = [];

		$imagesData = $this->getRawValue( $this->xmlNode );
		$captionsData = $this->getValueWithDefault( $this->xmlNode->{self::CAPTION_TAG_NAME} );

		foreach ( $imagesData as $key => $imageData ) {
			if ( empty ( $imageData ) ) {
				continue;
			}
			if( is_string($imageData) && PortableInfoboxDataBag::getInstance()->getGallery($imageData)) {
				$imageData = PortableInfoboxDataBag::getInstance()->getGallery($imageData);
			}
			$title = $this->getImageAsTitleObject( $imageData );
			$file = $this->getFilefromTitle( $title );
			if ( $title instanceof \Title ) {
				$this->getExternalParser()->addImage( $title->getDBkey() );
			}
			$data[] = [
				'url' => $this->resolveImageUrl( $file ),
				'name' => ( $title ) ? $title->getText() : '',
				'key' => ( $title ) ? $title->getDBKey() : '',
				'caption' => $captionsData[$key]
			];
		}
		$this->data = $data;
		return $this->data;
	}

	public function isEmpty() {
		$data = $this->getData();
		return !( $data  && count( $data ) > 0 );
	}

	public function resolveImageUrl( $file ) {
		return $file ? $file->getUrl() : '';
	}

	private function getImageAsTitleObject( $imageName ) {
		global $wgContLang;
		$title = \Title::makeTitleSafe(
			NS_FILE,
			ImageFilenameSanitizer::getInstance()->sanitizeImageFileName( $imageName, $wgContLang )
		);

		return $title;
	}

	private function getFilefromTitle( $title ) {
		return $title ? WikiaFileHelper::getFileFromTitle( $title ) : null;
	}

	protected function getIndexes($keys, $key) {
		$indexes = [];
		for ( $i = 0; $i < count ( $keys ); $i++ ) {
			if ( preg_match('/' . $key . '([0-9])*$/', $keys[$i], $out) ) {
				$indexes[] = isset( $out[1] ) ? $out[1] : 0;
			}
		}
		return array_unique($indexes);
	}

	protected function getRawInfoboxData( $sourceKey ) {
		$data = [];
		$indexes = $this->getIndexes( array_keys( $this->infoboxData ), $sourceKey );
		foreach($indexes as $index) {
			$data[$index] = isset( $this->infoboxData[ $sourceKey . $index ] ) ? $this->infoboxData[ $sourceKey . $index ] : $this->infoboxData[ $sourceKey ];
		}
		return $data;
	}

	protected function getInfoboxData( $key ) {
		$data = $this->getRawInfoboxData($key);
		$dataOut = [];
		foreach($data as $key => $val) {
			$dataOut[$key] = $this->getExternalParser()->parseRecursive( $val );
		}
		return $dataOut;
	}

}
