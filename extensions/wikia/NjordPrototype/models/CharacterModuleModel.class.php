<?php

class CharacterModuleModel {
	const WIKI_CHARACTER_IMAGE_MAX_WIDTH = 350;
	const WIKI_CHARACTER_IMAGE_MAX_HEIGHT = 350;
	const THUMBNAILER_SIZE_SUFIX = '350px-0';

	public $contentSlots = [ ];

	public function __construct( $pageName ) {
		$this->pageName = $pageName;
	}

	public function setFromContent( $content ) {
		$content = trim( $content );
		$elements = preg_split( '/$\R?^/m', $content );
		foreach ( $elements as $element ) {
			$attributes = preg_split( '/\|/m', $element );
			$item = new ContentEntity();

			$item->link = $this->getLink( $attributes );
			$item->image = $this->getImage( $attributes );
			$item->title = $this->getTitle( $attributes );
			$item->description = $this->getDescription( $attributes );

			$items[] = $item;
		}
		$this->contentSlots = $items;
	}

	/**
	 * @param $attributes
	 * @param $item
	 */
	protected function getLink( $attributes ) {
		$link = null;

		if ( !empty( $attributes[0] ) ) {
			$linkTitle = Title::newFromText( $attributes[0] );
			if ( $linkTitle instanceof Title ) {
				$link = $linkTitle->getLocalURL();
			}
		}

		return $link;
	}

	/**
	 * @param $attributes
	 * @param $item
	 */
	protected function getImage( $attributes ) {
		$image = null;

		$imageTitle = Title::newFromText( $attributes[1], NS_FILE );
		if ( $imageTitle instanceof Title ) {
			$imageFile = wfFindFile( $imageTitle );
			if ( $imageFile instanceof File && $imageFile->exists() ) {
				$image = wfReplaceImageServer( $imageFile->getThumbUrl( self::THUMBNAILER_SIZE_SUFIX ) );
			}
		}
		return $image;
	}

	/**
	 * @param $attributes
	 * @param $item
	 */
	protected function getTitle( $attributes ) {
		$title = null;

		if ( !empty( $attributes[2] ) ) {
			$title = $attributes[2];
		}

		return $title;
	}

	/**
	 * @param $attributes
	 * @param $item
	 */
	protected function getDescription( $attributes ) {
		$description = null;

		if ( !empty( $attributes[3] ) ) {
			$description = $attributes[3];
		}

		return $description;
	}
}
