<?php
/**
 * Created by adam
 * Date: 16.01.14
 */

class TemplatesFactory {

	/**
	 * Returns array with templates objects
	 * @param String $text WikiText to parse
	 */
	public function parse( $text ) {
		$result = [];
		while( $text ) {
			$text = $this->getTemplatesFromWikiText( $text, $matches );
		}
		foreach( $matches as $template ) {
			$result[] = new Template( $template );
		}
		return $result;
	}

	protected function getTemplatesFromWikiText( $text, &$matches ) {
		if ( !is_array( $matches ) ) { $matches = []; }
		$open = strpos( $text, '{{' );
		$close = strpos( $text, '}}' );
		if ( $open !== false ) {
			//template found
			if ( $open === 0 ) {
				//it starts here
				$second = strpos( $text, '{{', 2 );
				while ( $second !== false && $second < $close ) {
					//template inside found, extract it
					$this->getTemplatesFromWikiText( substr( $text, $second ), $matches );
					//remove inside template
					$inside = substr( $text, $second, $close - $second + 2 );
					$text = str_replace( $inside, '', $text );
					$second = strpos( $text, '{{', 2 );
					$close = strpos( $text, '}}' );
				}
				//extract final template
				$matches[] = substr( $text, $open, $close + 2 );
				//look for next template
				return substr( $text, $close + 2 );
			} else {
				//go to start
				return substr( $text, $open );
			}
		}
		return false;
	}

	protected function parseTemplate( $template ) {
		$stripped = strip_tags( $template, '<br><br/>' );
		$data = $this->explodeTemplate( $stripped );
		$templateKeys = [];
		if ( !empty( $data ) ) {
			foreach ( $data as $d ) {
				$keys = $this->parseInfoKey( $d );
				if ( !empty( $keys ) ) {
					$templateKeys = array_merge( $templateKeys, $keys );
				}
			}
			if ( !empty( $templateKeys ) ) {
				return [ trim( $data[0] ) => $templateKeys ];
			}
		}
		return [];
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