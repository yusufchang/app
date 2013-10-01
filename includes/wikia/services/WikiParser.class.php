<?php

class WikiParser {

	public function getSectionsFromParser( $parserOut, $content ) {
		$info = $parserOut->getSections();
		$result = [];

		for( $i = count( $info ) - 1; $i>=0; $i-- ) {
			$si = $info[ $i ];
			$offset = $si[ 'byteoffset' ];
			$sectionContent = substr( $content, $offset );
			$content = substr( $content, 0, $offset );

			if ( strpos( $sectionContent, '=' ) !== false ) {
				$content .= substr( $sectionContent, 0, strpos( $sectionContent, '=' ) );
			}

			$result[ $si[ 'line' ] ] = preg_replace( "|.*={{$si['level']}}.*={{$si['level']}}|s", '', $sectionContent );
		}
		$result[ 'noSectionHeading' ] = $content;

		return $result;
	}

	public function getSectionsStructure( $parserOut ) {
		$info = $parserOut->getSections();
		$result = [];
		$children = [];
		$oldtoc = 1;
		for ( $i = count( $info ) - 1; $i >= 0; $i-- ) {
			$current = $info[ $i ];
			$el = [ 'name' => $current[ 'line' ] ];
			if ( $current[ 'toclevel' ] != 1 ) {
				if ( $current[ 'toclevel' ] < $oldtoc && !empty( $children[ $oldtoc ] ) ) {
					$el[ 'subsections' ] = $children[ $oldtoc ];
					$children[ $oldtoc ] = [];
				}
				if ( !isset( $children[ $current[ 'toclevel' ] ] ) ) {
					$children[ $current[ 'toclevel' ] ] = [];
				}
				array_unshift( $children[ $current[ 'toclevel' ] ], $el );
			} else {
				if ( !empty( $children[ 2 ] ) ) {
					$el[ 'subsections' ] = $children[ 2 ];
					$children = [];
				}
				array_unshift( $result, $el );
			}
			$oldtoc = $current[ 'toclevel' ];
		}
		return $result;
	}
}
