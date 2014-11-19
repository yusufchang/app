<?php

class ContentEntity {
	public $link;
	public $image;
	public $title;
	public $description;
	public $cropposition;
	public $actor;
	public $actorlink;
	protected $actorUrl;
	protected $wikiUrl;
	protected $imagePath;
	protected $originalImagePath;

	public function toString() {
		return implode( '|', [ $this->link, $this->image, $this->cropposition, $this->title, $this->description, $this->actor, $this->actorlink ] );
	}

	public function getWikiUrl() {
		return $this->wikiUrl;
	}

	public function setWikiUrl($wikiUrl) {
		$this->wikiUrl = $wikiUrl;
	}

	public function getActorUrl() {
		return $this->actorUrl;
	}

	public function setActorUrl($actorUrl) {
		$this->actorUrl = $actorUrl;
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