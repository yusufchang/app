<?php
/**
 * Created by adam
 * Date: 16.01.14
 */

class Template {

	const SEPARATOR = '|';
	const KEY_VAL_SEPARATOR = '=';

	protected $infoKeys = [];
	protected $others = [];
	protected $raw;

	public function __construct( $template ) {
		$this->raw = $template;
		$parts = $this->explodeTemplate( $template );
		foreach( $parts as $part ) {
			if ( strpos( $part, self::KEY_VAL_SEPARATOR ) !== false ) {
				//infobox key - value pair
				$this->infoKeys[] = new InfoboxKey( $part );
			} else {
				//parse other stuff
				$this->others = array_merge( array_map( 'trim', explode( self::SEPARATOR, $part ) ), $this->others );
			}
		}
	}

	public function getName() {
		return (isset( $this->others[0] ) ) ? $this->others[0] : '';
	}

	protected function explodeTemplate( $template ) {
		$result = [];
		$el = null;
		$data = explode( '|', trim( $template, '{}' ) );
		foreach( $data as $key => $d ) {
			if ( $this->checkLinkElement( $d, $el ) ) {
				if ( !empty( $el ) ) {
					$result[] = $el;
					$el = null;
				}
				//add new one
				$el = $d;
			} else {
				//add to previous one, but only if its not first one
				$el .= ( !empty( $el ) ) ? '|' . $d : $d;
			}
		}
		//add last element
		if ( !empty( $el ) ) {
			$result[] = $el;
		}
		return $result;
	}

	protected function checkLinkElement( $current, $element ) {
		if ( strpos( $current, '=' ) !== false ) {
			//check if inside link
			if ( strpos( $element, '[[' ) !== false ) {
				$openings = preg_match_all( '|\[\[|sU', $element );
				$closings = preg_match_all( '|\]\]|sU', $element );
				if ( $openings > $closings ) {
					//some links are not closed
					if ( strpos( $current, '=' ) < strpos( $current, ']]' ) ) {
						return false;
					}
				}
			}
			return true;
		} else {
			return false;
		}
	}
}