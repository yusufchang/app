<?php

class PortableInfoboxQuery {

	public static function parserFunctionInit( \Parser &$parser ) {
		$parser->setFunctionHook( 'piq', 'PortableInfoboxQuery::render' );

		return true;
	}

	public static function render( &$parser, $arg1, $arg2, $arg3 ) {
		dd( PortableInfoboxSearchService::query( $arg1, 3 ) );

		return !empty( $arg1 ) ?
			print_r( PortableInfoboxSearchService::query( $arg1 ), true ) : "";
	}
}
