<?php

class ContentEntity {
	public $link;
	public $image;
	public $title;
	public $description;
	public $position;
	protected $wikiLink;
	protected $imagePath;
	protected $originalImagePath;

	public function toString() {
		return implode( '|', [ $this->link, $this->image, $this->title, $this->description ] );
	}

	public function getWikiLink() {
		return $this->wikiLink;
	}

	public function setWikiLink($wikiLink) {
		$this->wikiLink = $wikiLink;
	}

	public function getImagePath() {
		return $this->imagePath;
	}

	public function setImagePath($imagePath) {
		$this->imagePath = $imagePath;
	}

	public function getOriginalImagePath() {
		return $this->originalImagePath;
	}

	public function setOriginalImagePath($originalImagePath) {
		$this->originalImagePath = $originalImagePath;
	}
}