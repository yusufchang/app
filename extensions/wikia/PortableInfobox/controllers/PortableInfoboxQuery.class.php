<?php

class PortableInfoboxQuery {
	const TEMPLATE_PATH = 'extensions/wikia/PortableInfobox/queryTemplates/';
	const DEFAULT_WIDGET_TYPE = 'table';
	const LIST_IMAGE_THUMB = 30;


	private static $widgetTemplates = [
		'table' => 'queryTable.mustache',
		'list' => 'queryList.mustache',
		'gallery' => 'queryGallery.mustache',
		'infoboxTiles' => 'queryInfoboxtiles.mustache'
	];

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
		if ( !empty( $arg1 ) ) {
			$type = !empty( $arg2 ) && self::isValidWidgetType( $arg2 ) ? $arg2 : self::DEFAULT_WIDGET_TYPE;
			$data = self::getDataForWidgetType( PortableInfoboxSearchService::query( $arg1 ), $type );
			$template = self::getWidgetTemplate( $type );

			$marker = $parser->uniqPrefix() . "-" . self::PARSER_TAG_NAME . "-" . static::$markerNumber++ . "\x7f-QINU";
			static::$markers[ $marker ] = \MustacheService::getInstance()->render( $template, $data );

			return [ $marker, 'markerType' => 'nowiki' ];
		}

		return '';
	}

	/**
	 * @param $html string
	 * @param $infoboxData array
	 * @return string
	 */
	private static function renderInfoboxTilesHTML( $html, $infoboxData ) {
		return $html . ( new PortableInfoboxRenderService() )->renderInfobox( $infoboxData, null, null );
	}

	/**
	 * @param $query string
	 * @param $widgetType string
	 * @return array
	 */
	private static function getDataForWidgetType( $query, $widgetType ) {
		switch ( $widgetType ) {
			case self::DEFAULT_WIDGET_TYPE:
				return PIQTranslator::transform( $query )->toDataTable();
			case 'infoboxTiles':
				return [
					'content' => array_reduce(
						PIQTranslator::transform( $query )->toInfoboxData(),
						'PortableInfoboxQuery::renderInfoboxTilesHTML',
						''
					)
				];
				return '';
			case 'list':
				return [ 'items' => PIQTranslator::transform( $query )->withImage( self::LIST_IMAGE_THUMB )->toList() ];
			case 'gallery':
				return [ 'items' => PIQTranslator::transform( $query )->toList() ];
		}
	}

	/**
	 * @param $type string
	 * @return bool
	 */
	private static function isValidWidgetType( $type ) {
		return array_key_exists( $type, self::$widgetTemplates );
	}

	/**
	 * @param $type string
	 * @return string
	 */
	private static function getWidgetTemplate( $type ) {
		return self::TEMPLATE_PATH . self::$widgetTemplates[ $type ];
	}


}
