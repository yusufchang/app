<?php

class CharacterModuleModel {
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
			$item->link = $attributes[0];
			$item->image = $attributes[1];
			$item->title = $attributes[2];
			$item->description = $attributes[3];

			$items[] = $item;
		}
		$this->contentSlots = $items;
	}
}