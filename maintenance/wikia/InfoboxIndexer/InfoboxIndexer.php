<?php
/**
 * Created by adam
 * Date: 19.12.13
 */

require_once( dirname( __FILE__ ) . '/../../Maintenance.php' );
require_once( dirname( __FILE__ ) . '/DBHelper.class.php' );

class InfoboxIndexer extends Maintenance {

	protected $wikiId;
	/**
	 * @var DBHelper
	 */
	protected $db;

	public function execute() {
		if ( empty( $_ENV['SERVER_ID'] ) ) {
			$this->maybeHelp( true );
			die;
		}
		$this->wikiId = $_ENV['SERVER_ID'];
		$this->db = new DBHelper();

		$ids = $this->getIDs();
		$ids = array_unique( $ids );
		echo count($ids)." articles found...\n";

		$this->db->deleteKeys( $this->wikiId );
		$batches = array_chunk( $ids, 100 );
		foreach( $batches as $key => $batch ) {
			$keys = $this->parseArticlesForTemplate( $batch );
			echo count($keys)." articles with infoboxes found...\nSaving...\n";
			$this->db->setKeys( $this->wikiId, $keys );
			echo "Batch {$key} done.\n";
		}

		echo "done.\n";
	}

	protected function parseArticlesForTemplate( $ids ) {
		$result = [];
		foreach( $ids as $id ) {
			$title = Title::newFromID( $id );
			if ( $title && $title->getNamespace() === NS_MAIN ) {
				$article = Article::newFromID( $id );
				if ( $article ) {
					$raw = $article->getPage()->getRawText();
					$result[ $id ] = $this->getInfoKeys($raw);
				}
			}
		}
		return $result;
	}

	protected function getInfoKeys( $text ) {
		$result = [];
		while( $text ) {
			$text = $this->getTemplatesFromWikiText( $text, $matches );
		}
		$templates = array_unique( $matches );
		foreach( $templates as $template ) {
			$result = array_merge( $result, $this->parseTemplate( $template ) );
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
			if ( strpos( $d, '=' ) !== false ) {
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

	protected function parseInfoKey( $text ) {
		if ( strpos( $text, '=' ) !== false ) {
			$d = trim( $text );
			$cell = explode( '=', $d );
			//key shouldnt be longer then 4 words, if it's its probably not a infobox key
			if ( str_word_count( $cell[0] ) < 4 ) {
				$sanitizedValues = $this->sanitizeValue( $cell[1] );
				$result = [];
				foreach( $sanitizedValues as $val ) {
					$result[] = array_merge( [ 'key' => $this->sanitizeInfoKey( $cell[0] ) ], $val );
				}
				return $result;
			}
		}
		return false;
	}

	protected function sanitizeInfoKey( $key ) {
		$sanitized = strip_tags( $key );
		$sanitized = str_replace( ['[', '\'\'\'', '\'\'', '"', ']', '(', ')' ], '', trim( $sanitized ) );
		return $sanitized;
	}

	protected function sanitizeValue( $value ) {
		$result = [];
		$sanitized = str_replace( [ '\'\'\'', '\'\'', '"' ], '', trim( $value ) );
		//edge case with whitespace in tag
		$sanitized = preg_replace( '|<br\s+\/*>|sU', '<br>', $sanitized );
		if ( strpos( $sanitized, '<br>' ) !== false ) {
			$list = explode( '<br>', $sanitized );
		} elseif ( strpos( $sanitized, '<br/>' ) !== false ) {
			$list = explode( '<br/>', $sanitized );
		} else {
			$list = [ $sanitized ];
		}
		foreach( $list as $element ) {
			//add link handling
			//add template handling, so it dont break further parsing
			$el = [];
			//lets not do this for files
			$addData = $this->handleAdditionalInfo( $element );
			if ( !empty( $addData ) ) {
				$el[ 'add' ] = $addData;
			}
			$linkData = $this->handleLinks( $element );
			if ( !empty( $linkData ) ) {
				$el[ 'link' ] = $linkData;
			}
			$el[ 'val' ] = trim( $element );
			$result[] = $el;
		}
		return $result;
	}

	protected function handleLinks( &$element ) {
		$result = [];
		if ( strpos( $element, '[[' ) !== false ) {
			//at least one linke there
			//lets match all links!
			preg_match_all( '|\[\[(.*)\]\]|sU', $element, $matches );
			foreach( $matches[0] as $match ) {
				$trimmed = trim( $match, '[]' );
				$linkInfo = explode( '|', $trimmed );
				if ( preg_match( '|^.*\.\w{3}$|', $linkInfo[0] ) ) {
					//file link
					$name = str_replace( [ 'File:', 'Image:' ], '', $linkInfo[0] );
					$result[] = [
						'type' => 'file',
					];
				} else {
					$name = isset( $linkInfo[1] ) ? $linkInfo[1] : $linkInfo[0];
					$result[] = [
						'linksTo' => $linkInfo[0],
						'string' => $name,
						'type' => 'link'
					];
				}
				$element = str_replace( $match, $name, $element );
			}
			return $result;
		}
		return false;
	}

	protected function handleAdditionalInfo( &$element ) {
		if ( !preg_match( '|^.*\.\w{3}$|', $element ) && strpos( $element, '(' ) !== false ) {
			//brackets means it some kind of additional info we should extract
			preg_match( '|(\ (.*\))|s', $element, $matches );
			//remove this from main string
			if ( isset( $matches[0] ) ) {
				$element = str_replace( $matches[0], '', $element );
				$add = trim( $matches[0], '()' );
				return trim( $add );
			}
		}
		return false;
	}

	protected function getIDs() {
		$templates = $this->getTemplates();
		$ids = $this->db->getArticleIdsForTemplates( $templates );

		return $ids;
	}

	protected function getTemplates( $force = false ) {
		$templates = $this->db->getTemplates( $this->wikiId );
		if ( empty( $templates ) || $force ) {
			$allTemps = $this->db->getTemplatesList();
			$temps = [];
			//validate templates
			foreach( $allTemps as $temp ) {
				$tempTitle = Title::newFromText( $temp, NS_TEMPLATE );
				if ( $tempTitle ) {
					$article = new Article( $tempTitle );
					$content = $article->getPage();
					$wt = $content->getRawText();
					if ( $this->checkIfInfoboxStructure($wt) ) {
						$temps[] = $temp;
					}
				}
			}
			$this->db->deleteTemplates( $this->wikiId );
			$this->db->setTemplates( $this->wikiId, $temps );
			$templates = $temps;
		}
		return $templates;
	}

	protected function checkIfInfoboxStructure( $wikiText ) {
		wfProfileIn( __METHOD__ );
		$fir = preg_match_all( '|\|.*{{{(.*)\|}}}.*\|.*{{{(.*)}}}|sU', $wikiText, $matches );
		$sec = preg_match_all( '|\'\'\'(.*)\'\'\'.*\|.*{{{(.*)}}}|sU', $wikiText, $matches_2 );
		wfProfileOut(__METHOD__);
		return $fir || $sec;
	}
}

$maintClass = 'InfoboxIndexer';
require( RUN_MAINTENANCE_IF_MAIN );
