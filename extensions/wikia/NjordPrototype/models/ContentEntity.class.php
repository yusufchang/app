<?php

class ContentEntity {
	public $link;
	public $image;
	public $title;
	public $description;
	public $imagePath;
	public $originalImagePath;

	public function toString() {
		return implode( '|', [ $this->link, $this->image, $this->title, $this->description ] );
	}
}