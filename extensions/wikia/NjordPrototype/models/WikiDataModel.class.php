<?php

class WikiDataModel {
	private $pageName;
	private $imageName;
	private $cropPosition;

	public $imagePath;
	public $title;
	public $description;

	const WIKI_HERO_IMAGE_PROP_ID = 10001;
	const WIKI_HERO_TITLE_PROP_ID = 10002;
	const WIKI_HERO_DESCRIPTION_ID = 10003;
	const WIKI_HERO_IMAGE_CROP_POSITION_ID = 10004;

	const WIKI_HERO_IMAGE_MAX_WIDTH = 1600;
	const WIKI_HERO_IMAGE_MAX_HEIGHT = 500;
	const DEFAULT_IMAGE_CROP_POSITION = 0.3;

	public function __construct( $pageName ) {
		$this->pageName = $pageName;
	}

	public function setFromAttributes( $attributes ) {
		$this->imageName = !empty( $attributes[ 'imagename' ] ) ? $attributes[ 'imagename' ] : null;
		$this->title = !empty( $attributes[ 'title' ] ) ? $attributes[ 'title' ] : null;
		$this->description = !empty( $attributes[ 'description' ] ) ? $attributes[ 'description' ] : null;
		$this->cropPosition = !empty( $attributes[ 'cropposition' ] ) ? $attributes[ 'cropposition' ] : null;

		$this->initializeImagePath( $this->cropPosition );
	}

	public function storeInProps() {
		$pageId = Title::newFromText( $this->pageName )->getArticleId();

		wfSetWikiaPageProp( self::WIKI_HERO_IMAGE_PROP_ID, $pageId, $this->imageName );
		wfSetWikiaPageProp( self::WIKI_HERO_TITLE_PROP_ID, $pageId, $this->title );
		wfSetWikiaPageProp( self::WIKI_HERO_DESCRIPTION_ID, $pageId, $this->description );
		wfSetWikiaPageProp( self::WIKI_HERO_IMAGE_CROP_POSITION_ID, $pageId, $this->cropPosition );
	}

	public function getFromProps() {
		$pageId = Title::newFromText( $this->pageName )->getArticleId();

		$this->imageName = wfGetWikiaPageProp( self::WIKI_HERO_IMAGE_PROP_ID, $pageId );
		$this->title = wfGetWikiaPageProp( self::WIKI_HERO_TITLE_PROP_ID, $pageId );
		$this->description = wfGetWikiaPageProp( self::WIKI_HERO_DESCRIPTION_ID, $pageId );
		$this->cropPosition = wfGetWikiaPageProp( self::WIKI_HERO_IMAGE_CROP_POSITION_ID, $pageId );

		$this->initializeImagePath( $this->cropPosition );
	}

	/**
	 * @param $imageName
	 */
	private function initializeImagePath( $cropPosition ) {
		$imageTitle = Title::newFromText( $this->imageName, NS_FILE );
		$file = wfFindFile( $imageTitle );
		if ( $file && $file->exists() ) {
			$this->imagePath = $file->getThumbUrl(
				$this->getThumbSuffix(
					$file,
					self::WIKI_HERO_IMAGE_MAX_WIDTH,
					self::WIKI_HERO_IMAGE_MAX_HEIGHT,
					self::DEFAULT_IMAGE_CROP_POSITION
				) );
		} else {
			$this->imageName = null;
			$this->imagePath = null;
		}
	}

	private function getThumbSuffix( File $file, $expectedWidth, $expectedHeight, $crop ) {
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
		$top = round( $originalHeight * $crop );
		$bottom = $top + $height;
		return "{$width}px-$left,$right,$top,$bottom";
	}

	public function storeInPage() {
		$pageTitleObj = Title::newFromText( $this->pageName );
		$pageArticleObj = new Article( $pageTitleObj );

		$articleContents = $pageArticleObj->getContent();

		// Remove the original hero text
		$newContent = mb_ereg_replace( '<hero(.*?)/>', '', $articleContents, 'mi' );

		// Prepend the hero tag
		$heroTag = Xml::element( 'hero', $attribs = [
			'title' => $this->title,
			'description' => $this->description,
			'imagename' => $this->imageName,
			'cropposition' => $this->cropPosition
		] );
		$newContent = $heroTag . PHP_EOL . $newContent;

		// save and purge
		$pageArticleObj->doEdit( $newContent, '' );
		$pageArticleObj->doPurge();

	}

	public function getImageName() {
		return $this->imageName;
	}

	public function setImageName( $imageName ) {
		$this->imageName = $imageName;
	}

	public function getImagePath() {
		return $this->imagePath;
	}

	public function setImagePath( $imagePath ) {
		$this->imagePath = $imagePath;
	}
}
