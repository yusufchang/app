<?php
/**
 * Created by adam
 * Date: 16.01.14
 */

class InfoboxKey {

	const KEY_VAL_SEPARATOR = '=';

	protected $rawKey;
	protected $rawValue;

	protected $key;
	protected $value;
	protected $images = [];
	protected $links = [];
	protected $additionalValue = [];

	public function __construct( $text ) {
		$info = $this->explodeKey( $text );
		//key part
		$this->rawKey = $info[0];
		$this->rawValue = $info[1];

		$this->key = $this->sanitizeKey( $info[0] );
		$this->value = $this->sanitizeValue( $info[1] );
	}

	protected function explodeKey( $text ) {
		//explode on first one as we expect key = value pattern
		$result = [];
		$sep = strpos( $text, self::KEY_VAL_SEPARATOR );
		$result[] = substr( $text, 0, $sep );
		$result[] = substr( $text, $sep + 1 );
		return $result;
	}

	protected function sanitizeKey( $value ) {
		$sanitized = strip_tags( $value );
		$sanitized = str_replace( ['[', '\'\'\'', '\'\'', '"', ']', '(', ')' ], '', trim( $sanitized ) );
		return $sanitized;
	}

	protected function sanitizeValue( $value ) {
		$result = [];
		$sanitized = str_replace( [ '\'\'\'', '\'\'', '"' ], '', trim( $value ) );
		//edge case with whitespace in tag
		$sanitized = preg_replace( '|<br\s+\/*>|sU', '<br>', $sanitized );
		if ( strpos( $sanitized, '<br>' ) !== false ) {
			$list = explode( '<br>', $sanitized );
		} elseif ( strpos( $sanitized, '<br/>' ) !== false ) {
			$list = explode( '<br/>', $sanitized );
		} else {
			$list = [ $sanitized ];
		}
		foreach( $list as $element ) {
			$element = $this->parseImages( $element );
			$element = $this->parseLinks( $element );
			$element = $this->parseAdditionalInfo( $element );
			$result[] = trim( $element );
		}
		return $result;
	}

	protected function parseImages( $element ) {
		//images as links
		if ( preg_match_all( '|\[\[[.\S]*\.\w{3}[\|]*.*\]\]|U', $element, $match ) ) {
			foreach( $match[0] as $image ) {
				$trimmed = trim( $image, '[]' );
				//get additional info
				$imageInfo = explode( '|', $trimmed );
				$name = str_replace( [ 'File:', 'Image:' ], '', $imageInfo[0] );
				if ( !empty( $name ) && !in_array( $name, $this->images ) ) {
					$this->images[] = $name;
				}
				//remove from element
				$element = str_replace( $image, '', $element );
			}
			return $element;
		}
		//try to find anything that looks like image
		if ( preg_match_all( '|[.\S]*\.\w{3}|', $element, $match ) ) {
			//get images string
			foreach( $match[0] as $image ) {
				//take only image name
				if ( $sep = strpos( $image, '|' ) ) {
					$image = substr( $image, 0, $sep );
				}
				//remove wikitext image, file indicators
				$image = str_replace( [ 'File:', 'Image:' ], '', $image );
				//if we found distinct image, add it to list
				if ( !empty( $image ) && !in_array( $image, $this->images ) ) {
					$this->images[] = $image;
				}
				//remove from element
				$element = str_replace( $image, '', $element );
			}
		}
		return $element;
	}

	protected function parseLinks( $element ) {
		if ( strpos( $element, '[[' ) !==false ) {
			preg_match_all( '|\[\[(.*)\]\]|sU', $element, $matches );
			foreach( $matches[0] as $match ) {
				$trimmed = trim( $match, '[]' );
				$linkInfo = explode( '|', $trimmed );
				$name = isset( $linkInfo[1] ) ? $linkInfo[1] : $linkInfo[0];
				$this->links[] = [ 'string' => $name, 'linksTo' => $linkInfo[0] ];
				$element = str_replace( $match, $name, $element );
			}
		}
		return $element;
	}

	protected function parseAdditionalInfo( $element ) {
		if ( strpos( $element, '(' ) !== false ) {
			preg_match_all( '|(\(.*\))|s', $element, $matches );
			foreach ( $matches[0] as $bracket ) {
				$value = trim( $bracket, '()' );
				$this->additionalValue[] = trim( $value );
				$element = str_replace( $bracket, '', $element );
			}
		}
		return $element;
	}
}
