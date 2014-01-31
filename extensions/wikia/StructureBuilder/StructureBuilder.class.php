<?php

class StructureBuilder {

	const ALBUM_TEMPLATE_NAME = 'Album';
	const SONG_TEMPLATE_NAME = 'Song';
	const ARTIST_TEMPLATE_NAME = 'ArtistHeader';

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
	protected $excludedAlbums = [ 'Group Members', 'Other Songs', 'Additional information', 'Featured Songs' ];

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
		$metadata = [];
		$type = null;
		foreach ( $this->templatesData as $template ) {
			//album page
			if ( $template->getName() === self::ALBUM_TEMPLATE_NAME ) {
				$type = self::ALBUM_TEMPLATE_NAME;
				$infos = $template->getInfobox();
				foreach( $infos as $info ) {
					if ( in_array( $info['key'], ['Album', 'Artist', 'Genre', 'Length', 'Cover', 'iTunes']) ) {
						$metadata[] = $info;
					}
				}
			}

			if ( $template->getName() === self::ARTIST_TEMPLATE_NAME ) {
				$type = self::ARTIST_TEMPLATE_NAME;
				$infos = $template->getInfobox();
				foreach( $infos as $info ) {
					if ( in_array( $info['key'], ['pic', 'iTunes']) ) {
						$metadata[] = $info;
					}
				}
			}

			if ( $template->getName() === self::SONG_TEMPLATE_NAME ) {
				$type = self::SONG_TEMPLATE_NAME;
				$metadata['lyrics'] = $this->lyrics;
			}
		}
		$result['metadata'] = $metadata;
		//get list
		if ( $type === self::ALBUM_TEMPLATE_NAME ) {
			$result['songs'] = $this->albums;
		}
		if ( $type === self::ARTIST_TEMPLATE_NAME ) {
			foreach ( $this->albumsDates as $album => $date ) {
				if ( !in_array( $album, $this->excludedAlbums) ) {
					$albums[] = [ 'name' => $album, 'release' => $date ];
				}
			}
			$result['albums'] = $albums;
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
			if ( preg_match( '|^(.*)\((\d{4})\).*$|', $sectionInfo['line'], $match ) ) {
				$name = trim( $match[1] );
				$date = trim( $match[2] );
			}

			$this->artistAlbums[] = $name;
			$this->albumsDates[ $name ] = $date;
		}
	}

}
