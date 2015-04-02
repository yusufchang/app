<?php
class PortableInfoboxParserTagController extends WikiaController {
	const PARSER_TAG_NAME = 'infobox';

	/**
	 * @desc Parser hook: used to register parser tag in MW
	 *
	 * @param Parser $parser
	 * @return bool
	 */
	public static function parserTagInit( Parser $parser ) {
		$parser->setFunctionTagHook( self::PARSER_TAG_NAME, [new static(), 'renderInfobox'], 0);

		//$parser->setHook( self::PARSER_TAG_NAME, [new static(), 'renderInfobox'] );
		return true;
	}

	/**
	 * @desc Renders Infobox
	 *
	 * @param String $text
	 * @param Array $params
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @returns String $html
	 */
	public function renderInfobox( &$parser, $frame, $content, $attributes ) {
		$connector = new InfoboxServiceConnector();
		$data = $frame->getNamedArguments();
		$html = $connector->getHtmlBySource('<'.self::PARSER_TAG_NAME.'>'.$content.'</'.self::PARSER_TAG_NAME.'>', $data);
		return $html;
	}
}
