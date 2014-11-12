<?php

class CharacterModuleModel {
	const WIKI_CHARACTER_MODULE_TITLE_PROP_ID = 10005;
	const WIKI_CHARACTER_MODULE_CONTENTS_PROP_ID = 10006;

	const WIKI_CHARACTER_IMAGE_MAX_SIDE_LENGTH = 250;

	public $title = '';
	public $contentSlots = [ ];

	public function __construct( $pageName ) {
		$this->pageName = $pageName;
	}

	public function isEmpty() {
		return empty( $this->title ) && empty( $this->contentSlots );
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

		$contentSlots = [ ];
		foreach ( $this->contentSlots as $contentSlot ) {
			$newSlot = $contentSlot;
			$newSlot->imagePath = null;
			$newSlot->originalImagePath = null;
			$contentSlots [] = $newSlot;
		}

		wfSetWikiaPageProp( self::WIKI_CHARACTER_MODULE_TITLE_PROP_ID, $pageId, $this->title );
		wfSetWikiaPageProp( self::WIKI_CHARACTER_MODULE_CONTENTS_PROP_ID, $pageId, json_encode( $contentSlots ) );
	}

	public function getFromProps() {
		$pageId = Title::newFromText( $this->pageName )->getArticleId();

		$this->title = wfGetWikiaPageProp( self::WIKI_CHARACTER_MODULE_TITLE_PROP_ID, $pageId );
		$items = json_decode( wfGetWikiaPageProp( self::WIKI_CHARACTER_MODULE_CONTENTS_PROP_ID, $pageId ) );
		$contentSlots = [ ];
		foreach ( $items as $item ) {
			$contentSlot = new ContentEntity();
			$contentSlot->link = $item->link;
			$contentSlot->image = $item->image;
			$contentSlot->title = $item->title;
			$contentSlot->description = $item->description;
			$contentSlots [] = $contentSlot;
		}
		$this->contentSlots = $contentSlots;

		$this->initializeImagePaths();
	}

	public function storeInPage() {
		$pageTitleObj = Title::newFromText( $this->pageName );
		$pageArticleObj = new Article( $pageTitleObj );

		$articleContents = $pageArticleObj->getContent();

		// Remove the original text; if there's a newline at the end, we will strip it
		// as new tag has one and we don't want a barrage of newlines
		$newContent = mb_ereg_replace( '<momcharactermodule(.*?)>(.*?)</momcharactermodule>\n?', '', $articleContents, 'mi' );

		$entities = [];
		$entities [] = '';
		foreach ( $this->contentSlots as $contentSlot ) {
			$entities [] = $contentSlot->toString();
		}
		$entities [] = '';

		// Prepend the character module tag
		$characterModuleTag = Xml::element( 'momcharactermodule', $attribs = [ 'title' => $this->title ], $contents = implode( PHP_EOL, $entities ) );

		$newContent = $characterModuleTag . PHP_EOL . $newContent;

		// save and purge
		$pageArticleObj->doEdit( $newContent, '' );
		$pageArticleObj->doPurge();

	}

	protected function getImagePaths( $imageName ) {
		$imagePath = null;
		$originalImagePath = null;

		$imageTitle = Title::newFromText( $imageName, NS_FILE );
		$file = wfFindFile( $imageTitle );
		if ( $file && $file->exists() ) {
			$imagePath = $file->getThumbUrl( $this->getThumbSuffix( $file, self::WIKI_CHARACTER_IMAGE_MAX_SIDE_LENGTH ) );
			$originalImagePath = $file->getFullUrl();
		}

		return [ 'imagePath' => $imagePath, 'originalImagePath' => $originalImagePath ];
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

	public function initializeImagePaths() {
		foreach ( $this->contentSlots as &$contentEntity ) {
			$imageData = $this->getImagePaths( $contentEntity->image );
			$contentEntity->imagePath = $imageData['imagePath'];
			$contentEntity->originalImagePath = $imageData['originalImagePath'];
		}
	}

	private function getThumbSuffix( File $file, $expectedSideLength ) {
		$originalHeight = $file->getHeight();
		$originalWidth = $file->getWidth();
		$originalRatio = $originalWidth / $originalHeight;
		$ratio = 1;
		if ( $originalRatio > $ratio ) {
			$height = ( $originalHeight > $expectedSideLength ) ? $expectedSideLength : $originalHeight;
			$width = $height;
		} else {
			$width = ( $originalWidth > $expectedSideLength ) ? $expectedSideLength : $originalWidth;
			$height = $width;
		}
		$width = round( $width );

		$top = ( $originalHeight > $originalWidth )
			? round(($originalHeight - $originalWidth)/2)
			: 0;
		$bottom = $originalHeight - $top;
		$left = ( $originalWidth > $originalHeight )
			? round(($originalWidth - $originalHeight)/2)
			: 0;
		$right = $originalWidth - $left;

		return "{$width}px-$left,$right,$top,$bottom";
	}
}
