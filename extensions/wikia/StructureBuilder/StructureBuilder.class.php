<?php

class StructureBuilder {

	const ALBUM_TEMPLATE_NAME = 'Album';

	protected $lyrics;
	/**
	 * @var Template[] $templatesData
	 */
	protected $templatesData;

	public function __construct() {
	}

	/**
	 * @param int $id Article id
	 */
	public function parse( $id ) {
		$wikiText = $this->getWikiText( $id );

		$builder = new TemplatesFactory();
		$this->templatesData = $builder->parse( $wikiText );

		$this->parseLyrics( $wikiText );
		$this->parseAlbums( $wikiText );

		return $wikiText;
	}

	/**
	 * Return wikitext for article, works only for namespace 0 (main)
	 * @param int $id
	 * @return false|String WikiText for given article id
	 */
	protected function getWikiText( $id ) {
		$title = Title::newFromID( $id );
		if ( $title && $title->getNamespace() === NS_MAIN ) {
			$article = Article::newFromID( $id );
			if ( $article ) {
				return $article->getPage()->getRawText();
			}
		}
		return false;
	}

	protected function parseLyrics( $wikiText ) {
		if ( preg_match( '|<lyrics>(.*)<\/lyrics>|sU', $wikiText, $matches ) ) {
			$this->lyrics = trim( $matches[1] );
			return true;
		}
		return false;
	}

	protected function parseAlbums( $wikiText ) {
		foreach( $this->templatesData as $template ) {
			if ( $template->getName() === self::ALBUM_TEMPLATE_NAME ) {
				preg_match_all( '|#.*\[\[(.*)\|(.*)\]\]|sU', $wikiText, $matches );
				for ( $i = 0; $i < count( $matches[0] ); $i++ ) {
					$this->albums[] = [ 'article' => $matches[1][$i], 'name' => $matches[2][$i] ];
				}
				return true;
			}
		}
		return false;
	}

}
