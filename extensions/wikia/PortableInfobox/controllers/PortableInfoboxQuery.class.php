<?php

class PortableInfoboxQuery {

	const PARSER_TAG_NAME = 'piq';
	private static $markers;
	private static $markerNumber = 0;

	public static function parserFunctionInit( \Parser &$parser ) {
		$parser->setFunctionHook( 'piq', 'PortableInfoboxQuery::render' );

		return true;
	}

	public static function replacePIQMarkers( &$parser, &$text ) {
		if ( !empty( static::$markers ) ) {
			$text = strtr( $text, static::$markers );
		}

		return true;
	}

	/**
	 * @param Parser $parser
	 * @param $arg1
	 * @param $arg2
	 * @param $arg3
	 *
	 * @return array
	 */
	public static function render( &$parser, $arg1, $arg2, $arg3 ) {
		$marker = $parser->uniqPrefix() . "-" . self::PARSER_TAG_NAME . "-" . static::$markerNumber++ . "\x7f-QINU";
		static::$markers[ $marker ] = ""; //<---- put your html here

		return [ $marker, 'markerType' => 'nowiki' ];
	}
}
