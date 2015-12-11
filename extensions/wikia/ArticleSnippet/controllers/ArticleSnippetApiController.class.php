<?php

class ArticleSnippetApiController extends WikiaApiController {

	public function getArticleSnippet() {
		$pageTitle = $this->getVal( 'pageTitle' );

		$title = Title::newFromText( $pageTitle );

		if ( !$title->exists() ) {
			$this->response->setVal( 'status', false );
			return false;
		} elseif ( $title->isRedirect() ) {
			$title = WikiPage::factory( $title )->getRedirectTarget();
			if ( !$title instanceof Title ) {
				$this->response->setVal( 'status', false );
				return false;
			}
		}

		$portableInfoboxDataService = PortableInfoboxDataService::newFromTitle( $title );

		$this->response->setVal( 'articleSnippetData', $portableInfoboxDataService->getData() );
	}
}
