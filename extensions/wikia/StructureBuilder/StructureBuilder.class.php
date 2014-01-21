<?php

class StructureBuilder {

	const ALBUM_TEMPLATE_NAME = 'Album';

	protected $lyrics;
	protected $albums = [];
	protected $artistAlbums = [];
	protected $albumsDates = [];
	/**
	 * @var Template[] $templatesData
	 */
	protected $templatesData;

	protected $wikiText;
	protected $sectionsInfo;
	/**
	 * @var Title $title
	 */
	protected $title;

	public function __construct() {
	}

	/**
	 * @param int $id Article id
	 */
	public function parse( $id ) {
		wfProfileIn( __METHOD__ );
		$this->getWikiData( $id );

		$builder = new TemplatesFactory();
		$this->templatesData = $builder->parse( $this->wikiText );

		$this->parseLyrics();
		$this->parseAlbums();
		$this->parseSectionNames();
		wfProfileOut( __METHOD__ );
	}

	public function getResult() {
		$result = [];
//		if ( !empty( $this->artistAlbums ) ) {
//			$result[ 'albums' ]['fromSections'] = $this->artistAlbums;
//		}
//		if ( !empty( $this->albums ) ) {
//			foreach( $this->albums as $album ) {
//				$result[ 'albums' ][] = $album[ 'name' ];
//			}
//		}
//		if ( !empty( $this->lyrics ) ) {
//			$result[ 'lyrics' ][] = $this->lyrics;
//		}
		foreach( $this->artistAlbums as $album ) {
			$result['albums'][$album] = [ 'release' => $this->albumsDates[ $album ] ];
		}

		if ( !empty( $this->templatesData ) ) {
//			$result[ 'keys' ] = [];
			foreach( $this->templatesData as $template ) {
				foreach( $template->getInfobox() as $info ) {
					if ( isset( $info['key'] ) ) {
//						$result['keys'][] = [ $info['key'] => $info['data']['value'] ];
					} else {
						if ( isset( $info[2] ) && in_array( $info[2], $this->artistAlbums ) ) {
							$result['albums'][$info[2]]['image'] = [ $info[1] ];
						}

					}
				}
			}
		}
		return $result;
	}

	/**
	 * Return wikitext for article, works only for namespace 0 (main)
	 * @param int $id
	 * @return false|String WikiText for given article id
	 */
	protected function getWikiData( $id ) {
		$this->title = Title::newFromID( $id );
		if ( $this->title && $this->title->getNamespace() === NS_MAIN ) {
			$article = Article::newFromID( $id );
			if ( $article ) {
				$this->sectionsInfo = $article->getParserOutput()->getSections();
				$this->wikiText = $article->getPage()->getRawText();
			}
		}
		return false;
	}

	protected function parseLyrics() {
		if ( preg_match( '|<lyrics>(.*)<\/lyrics>|sU', $this->wikiText, $matches ) ) {
			$this->lyrics = trim( $matches[1] );
			return true;
		}
		return false;
	}

	protected function parseAlbums() {
		foreach( $this->templatesData as $template ) {
			if ( $template->getName() === self::ALBUM_TEMPLATE_NAME ) {
				preg_match_all( '|#.*\[\[(.*)\|(.*)\]\]|sU', $this->wikiText, $matches );
				for ( $i = 0; $i < count( $matches[0] ); $i++ ) {
					$this->albums[] = [ 'article' => $matches[1][$i], 'name' => $matches[2][$i] ];
				}
				return true;
			}
		}
		return false;
	}

	protected function parseSectionNames() {
		foreach( $this->sectionsInfo as $sectionInfo ) {
			//get only highest level sections
			$name = $sectionInfo['line'];
			$date = '';
			//extract date
			if ( preg_match( '|^(.*)\((\d{4})\)$|', $sectionInfo['line'], $match ) ) {
				$name = trim( $match[1] );
				$date = trim( $match[2] );
			}

			$this->artistAlbums[] = $name;
			$this->albumsDates[ $name ] = $date;
		}
	}

}
