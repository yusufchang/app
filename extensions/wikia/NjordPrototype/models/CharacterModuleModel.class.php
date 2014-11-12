<?php

class CharacterModuleModel {
	const WIKI_CHARACTER_MODULE_TITLE_PROP_ID = 10004;
	const WIKI_CHARACTER_MODULE_CONTENTS_PROP_ID = 10005;

	const WIKI_CHARACTER_IMAGE_MAX_WIDTH = 350;
	const WIKI_CHARACTER_IMAGE_MAX_HEIGHT = 350;
	const THUMBNAILER_SIZE_SUFFIX = '350px-0';

	public $title = '';
	public $contentSlots = [ ];

	public function __construct( $pageName ) {
		$this->pageName = $pageName;
	}

	public function setFromAttributes( $attributes ) {
		$this->title = !empty( $attributes['title'] ) ? $attributes['title'] : null;
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
		$this->initializeImagePaths();
	}

	public function storeInProps() {
		$pageId = Title::newFromText( $this->pageName )->getArticleId();

		wfSetWikiaPageProp( self::WIKI_CHARACTER_MODULE_TITLE_PROP_ID, $pageId, $this->imageName );
		wfSetWikiaPageProp( self::WIKI_CHARACTER_MODULE_CONTENTS_PROP_ID, $pageId, json_encode( $this->contentSlots ) );
	}

	public function getFromProps() {
		$pageId = Title::newFromText( $this->pageName )->getArticleId();

		$this->title = wfGetWikiaPageProp( self::WIKI_CHARACTER_MODULE_TITLE_PROP_ID, $pageId );
		$this->contentSlots = json_decode( wfGetWikiaPageProp( self::WIKI_CHARACTER_MODULE_CONTENTS_PROP_ID, $pageId ) );

		$this->initializeImagePaths();
	}

	function getImagePaths($imageName) {
		$imagePath = null;
		$originalImagePath = null;

		$imageTitle = Title::newFromText( $imageName, NS_FILE );
		$file = wfFindFile( $imageTitle );
		if ( $file && $file->exists() ) {
			$imagePath = $file->getThumbUrl(
				$this->getThumbSuffix(
					$file,
					self::WIKI_CHARACTER_IMAGE_MAX_WIDTH,
					self::WIKI_CHARACTER_IMAGE_MAX_HEIGHT
				) );
			$originalImagePath = $file->getFullUrl();
		}

		return [
			'imagePath' => $imagePath,
			'originalImagePath' => $originalImagePath
		];
	}

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

	protected function getImage( $attributes ) {
		$image = null;

		if ( !empty( $attributes[1] ) ) {
			$image = $attributes[1];
		}
		return $image;
	}

	protected function getTitle( $attributes ) {
		$title = null;

		if ( !empty( $attributes[2] ) ) {
			$title = $attributes[2];
		}

		return $title;
	}

	protected function getDescription( $attributes ) {
		$description = null;

		if ( !empty( $attributes[3] ) ) {
			$description = $attributes[3];
		}

		return $description;
	}

	protected function initializeImagePaths() {
		foreach ( $this->contentSlots as &$contentEntity ) {
			$imageData = $this->getImagePaths( $contentEntity->image );
			$contentEntity->imagePath = $imageData['imagePath'];
			$contentEntity->originalImagePath = $imageData['originalImagePath'];
		}
	}

	private function getThumbSuffix( File $file, $expectedWidth, $expectedHeight ) {
		$originalHeight = $file->getHeight();
		$originalWidth = $file->getWidth();
		$originalRatio = $originalWidth / $originalHeight;
		$ratio = $expectedWidth / $expectedHeight;
		if ( $originalRatio > $ratio ) {
			$width = round( $originalHeight * $ratio );
			$height = $originalHeight;
		} else {
			$width = $originalWidth;
			$height = round( $originalWidth / $ratio );
		}

		$width = ( $width > $expectedWidth ) ? $expectedWidth : $width;
		$left = 0;
		$right = $originalWidth;
		$top = 0;
		$bottom = $top + $height;
		return "{$width}px-$left,$right,$top,$bottom";
	}
}
