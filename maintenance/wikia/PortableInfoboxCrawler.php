<?php

$dir = dirname( __FILE__ );
require_once( $dir . '/../Maintenance.php' );
//require_once( $dir . '/../../extensions/wikia/PortableInfobox/PortableInfobox.setup.php' );
require_once( $dir . '/../../extensions/wikia/TemplateClassification/TemplateClassification.setup.php' );

class PortableInfoboxCrawler extends Maintenance {
	const LINK_REGEX = '/\[\[(.*?)\]\]/';

	public function execute() {
		global $wgCityId;

		$schema = [];
		$redirects = [];

		// for harry potter
		$templates = $this->getClassifiedInfoboxes();
		// for james bond
		//$templates = $this->getPortableInfoboxes();

		foreach ( $templates as $title ) {
			$redirectTitles = $title->getRedirectsHere( NS_TEMPLATE );

			if ( !empty($redirectTitles)) {
				foreach( $redirectTitles as $redirect ) {
					$templates[] = $redirect;
					$redirects[$redirect->getDBkey()] = $title->getDBkey();
				}
			}
		}

		$graph = '<graphs/harrypotter>';
		$file = fopen('harrypotter.nq', 'w');

		foreach ($templates as $templateTitle ) {
			$templateTitleText = $templateTitle->getText();
			$prefix = $templateTitle->getDBkey();
			$this->output("parsing $templateTitleText\n");

			$extractor = new \Flags\FlagsExtractor();

			if ( !$templateTitle->isRedirect() ) {
				// for harry potter
				$infoboxSource = $this->getClassifiedInfoboxSources($templateTitle, $templateTitleText, $extractor );
				// for james bond
				//$infoboxSource = $this->getPortableInfoboxSource( $templateTitle );
/*
				if ( empty ( $infoboxSource ) ) {
					$infoboxSource = $this->getClassifiedInfoboxSources( $templateTitle, $templateTitleText, $extractor );
					if ( empty ( $infoboxSource ) ) {
						continue;
					}
				}
*/

				$schema[$templateTitleText] = $infoboxSource;
				$schema[$templateTitleText]['pagesCount'] = 0;
			} else {
				if ( isset( $redirects[$prefix])) {
					$prefix = $redirects[$prefix];
				}
			}

			$pages = $templateTitle->getIndirectLinks();

			foreach ( $pages as $page ) {
				$subject = "$wgCityId:$page->page_id";

				$pageTitle = addslashes($page->page_title);

				$nqRow = "$subject <wikia:pagename> \"$pageTitle\" $graph .\n";
				$nqRow .= "$subject <wikia:infoboxtype> \"$prefix\" $graph .\n";

				fwrite($file, $nqRow);

				$title = Title::newFromText( $page->page_title );
				$schema[$templateTitleText]['pagesCount']++;

				$article = \Article::newFromTitle( $title, \RequestContext::getMain() );
				if ( $article && $article->exists() ) {
					$content = $article->fetchContent();
					$extractor->init( $content, $templateTitleText );
					$data = $extractor->getTemplate();

					if ( !isset( $data[0] ) ) {
						$extractor->init($content, $prefix);
						$data = $extractor->getTemplate();

						if ( !isset( $data[0] ) ) {
							continue;
						}
					}

					foreach ($data[0]['params'] as $key => $param) {
						if (empty($param)) {
							continue;
						}
						$attr = str_replace(' ', '_', $key);
						$predicate = "<$prefix/$attr>";
						$linkTitles = $this->getLinkTitle($param);
						if ( !empty( $linkTitles ) ) {
							foreach ( $linkTitles as $linkTitle ) {
								$linkTitle = Title::newFromText( $linkTitle );

								if ( !empty( $linkTitle ) ) {
									$articleId = $linkTitle->getArticleID();
									$object = "$wgCityId:$articleId";
									$nqRow = "$subject $predicate $object $graph .\n";
									fwrite( $file, $nqRow );
								}
							}
						} else {
							$objects = explode('<br/>', $param );
							foreach ( $objects as $obj ) {
								$objs = explode('<br>', $obj );
								foreach ( $objs as $object ) {
									$o = str_replace("\n", ' ', $object );
									$o = addslashes( $o );

									$nqRow = "$subject $predicate \"$o\" $graph .\n";
									fwrite( $file, $nqRow );
								}
							}
						}
					}
				}


			}
			var_dump("=============");
		}

		fclose($file);

		$schemaFile = fopen( 'schema', 'w' );
		$schemaText = '';
		foreach ( $schema as $name => $keys ) {
			$schemaText .= "$name \n";
			foreach ( $keys as $attr => $val ) {
				$schemaText .= "\t$attr\n";
			}
			$schemaText.="\n\n";
		}

		fwrite($schemaFile, $schemaText );
		fclose($schemaFile);

		var_dump('complete');
	}

	public function getClassifiedInfoboxes() {
		$templates = [];

		$tsc = new TemplatesSpecialController();
		$tsc->type = 'infobox';
		$all = $tsc->getAllTemplates();
		$classified = $tsc->getClassifiedTemplates( $all );
		$infoboxes =  $tsc->getTemplatesByType( $all, $classified );

		foreach ( $infoboxes as $infobox ) {
			$title = Title::newFromText($infobox['title'], NS_TEMPLATE);
			$templates[] = $title;
		}

		return $templates;
	}

	public function getPortableInfoboxes() {
		$templates = [];

		$piQueryPage = new AllinfoboxesQueryPage();
		$res = $piQueryPage->doQuery();

		$dbr = wfGetDB( DB_SLAVE );
		while ( $row = $dbr->fetchObject( $res ) ) {
			$title = Title::newFromText( $row->title, NS_TEMPLATE );
			$templates[] = $title;
		}

		return $templates;
	}

	public function getTemplatesText( $templates ) {
		$templateText = [];

		foreach ( $templates as $title ) {
			$templateText[] = $title->getText();
		}

		return $templateText;
	}

	public function getClassifiedInfoboxSources( $templateTitle, $templateTitleText, $extractor ) {
		$infoboxSource = [];

		$article = \Article::newFromTitle( $templateTitle, \RequestContext::getMain() );
		if ( $article && $article->exists() ) {
			$content = $article->fetchContent();
			$extractor->init( $content, $templateTitleText );
			$source = $extractor->getTemplate();

			if ( empty( $source ) ) {
				$extractor->init($content, $templateTitle->getDBkey());
				$source = $extractor->getTemplate();
			}

			if ( !empty( $source ) && isset($source[0]['params']) ) {
				$infoboxSource = $source[0]['params'];
			}
		}

		return $infoboxSource;
	}

	public function getPortableInfoboxSource( $templateTitle ) {
		$infoboxSource = array_flip(PortableInfoboxDataService::newFromTitle( $templateTitle )->getData()[0]['sources']);
		foreach ( $infoboxSource as $key => $source ) {
			$infoboxSource[$key] = [];
		}

		return $infoboxSource;
	}

	public function getLinkTitle( $param) {
		preg_match_all( self::LINK_REGEX, $param, $links );
		if ( !empty($links[1])) {
			$linkTitle = [];

			foreach ( $links[1] as $link ){
				$linkParts = explode('|', $link);
				$linkTitle[] = $linkParts[0];
			}

			return $linkTitle;
		}

		return [];
	}

}

$maintClass = "PortableInfoboxCrawler";
require_once( DO_MAINTENANCE );
