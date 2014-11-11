<?php

class NjordHooks {
	public static $templateDir;

	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'hero', 'NjordHooks::renderHeroTag' );
		$parser->setHook( 'momcharactermodule', 'NjordHooks::renderCharacterModuleContainerTag' );
		return true;
	}

	public static function onCreateNewWikiComplete( $params ) {
		if ( !empty( $params['city_id'] ) ) {
			WikiFactory::setVarByName( 'wgEnableNjordExt', $params['city_id'], true );
		}
		return true;
	}

	public static function renderHeroTag( $content, array $attributes, Parser $parser, PPFrame $frame ) {
		$wikiData = new WikiDataModel( Title::newMainPage()->getText() );
		$wikiData->setFromAttributes( $attributes );
		$wikiData->storeInProps();
		return '';
	}

	public static function renderCharacterModuleContainerTag( $content, array $attributes, Parser $parser, PPFrame $frame ) {
		$characterModel = new CharacterModuleModel( Title::newMainPage()->getText() );
		$characterModel->setFromContent( $content );
		return F::app()->renderView( 'NjordCharacter', 'index', [ 'characterModel' => $characterModel ] );
	}
}
