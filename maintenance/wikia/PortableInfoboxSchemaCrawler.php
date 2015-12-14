<?php

$dir = dirname( __FILE__ );
require_once( $dir . '/../Maintenance.php' );
require_once( $dir . '/../../extensions/wikia/PortableInfobox/PortableInfobox.setup.php' );

class PortableInfoboxCrawler extends Maintenance {
	const LINK_REGEX = '/\[\[(.*?)\]\]/';

	public function execute() {
		$piQueryPage = new AllinfoboxesQueryPage();
		$res = $piQueryPage->doQuery();

		$schema = [];
		$templates = [];
		$templatesText = [];

		$dbr = wfGetDB( DB_SLAVE );
		while ( $row = $dbr->fetchObject( $res ) ) {
			$title = Title::newFromText( $row->title, NS_TEMPLATE );
			$templates[] = $title;
			$templatesText[] = $title->getText();
		}

		foreach ($templates as $templateTitle ) {
			$templateTitleText = $templateTitle->getText();
			$this->output("parsing $templateTitleText\n");
			$infoboxSource = array_flip(PortableInfoboxDataService::newFromTitle( $templateTitle )->getData()[0]['sources']);
			foreach ( $infoboxSource as $key => $source ) {
				$infoboxSource[$key] = [];
			}
			$extractor = new \Flags\FlagsExtractor();

			$schema[$templateTitleText] = $infoboxSource;
			$schema[$templateTitleText]['pagesCount'] = 0;

			$pages = $templateTitle->getIndirectLinks();

			foreach ( $pages as $page ) {
				$title = Title::newFromText( $page->page_title );
				$schema[$templateTitleText]['pagesCount']++;

				$article = \Article::newFromTitle( $title, \RequestContext::getMain() );
				if ( $article && $article->exists() ) {
					$content = $article->fetchContent();
					$extractor->init( $content, $templateTitleText );
					$data = $extractor->getTemplate();

					foreach ($data[0]['params'] as $key => $param) {
						$linkTitles = $this->getLinkTitle($param);
						foreach ( $linkTitles as $linkTitle ) {
							$linkTitle = Title::newFromText($linkTitle);

							if ( !empty( $linkTitle ) ) {
								$linkData = PortableInfoboxDataService::newFromTitle( $linkTitle )->getData();

								if ( !empty ( $linkData ) ) {
									$linkedArticle = \Article::newFromTitle( $linkTitle, \RequestContext::getMain() );
									if ( !$linkedArticle || !$linkedArticle->exists() ) {
										continue;
									}

									$linkedContent = $linkedArticle->fetchContent();

									foreach ( $templatesText as $titleText ) {
										$extractor->init( $linkedContent, $titleText );
										$linkedInfobox = $extractor->getTemplate();
										if ( !empty( $linkedInfobox ) ) {
											if ( isset ( $schema[$templateTitleText][$key]['links'][$linkedInfobox[0]['name']] ) ) {
												$schema[$templateTitleText][$key]['links'][$linkedInfobox[0]['name']]++;
											} else {
												$schema[$templateTitleText][$key]['links'][$linkedInfobox[0]['name']] = 1;
											}
											break;
										}
									}
								}
							}
						}
					}
				}

				/*
				$title = Title::newFromText( $page->page_title );
				$infobox = PortableInfoboxDataService::newFromTitle( $title );

				$infobox->getRawData();die;*/
			}
			var_dump("=============");
		}

		print_r($schema);
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
