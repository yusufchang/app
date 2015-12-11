<?php

class ArticleSnippetApiController extends WikiaApiController {

	const HIGHLIGHTS_LIMIT = 5;

	private
		$articleSnippet = [
			'title' => '',
			'image' => '',
			'highlights' => [],
		];

	public function getArticleSnippet() {
		$pageTitle = urldecode( $this->getVal( 'pageTitle' ) );
		$title = $this->getValidTitleObjectOrDie( $pageTitle );

		if ( !$title ) {
			$this->response->setVal( 'articleSnippet', [] );
			return;
		}

		$portableInfoboxDataService = PortableInfoboxDataService::newFromTitle( $title );

		$this->response->setVal(
			'articleSnippet',
			$this->getSnippetFromDataSource( $portableInfoboxDataService )
		);
	}

	private function getValidTitleObjectOrDie( $pageTitle ) {
		$title = Title::newFromText( $pageTitle );

		if ( !$title instanceof Title ) {
			return false;
		} elseif ( !$title->exists() ) {
			return false;
		} elseif ( $title->isRedirect() ) {
			$title = WikiPage::factory( $title )->getRedirectTarget();
			if ( !$title instanceof Title ) {
				return false;
			}
		}

		return $title;
	}

	private function getSnippetFromDataSource( PortableInfoboxDataService $service ) {
		$data = $service->getData();

		if ( empty( $data ) ) {
			return $data;
		}

		foreach ( $data[0]['data'] as $field ) {
			if ( $field['type'] === 'title' ) {
				$this->articleSnippet['title'] = $field['data']['value'];
			} elseif ( $field['type'] === 'image' ) {
				$this->articleSnippet['image'] = $field['data'][0]['url'];
			} elseif ( $field['type'] === 'data' )
			{
				$this->articleSnippet['highlights'][] = $field['data'];
			} elseif ( $field['type'] === 'group' )
			{
				foreach ( $field['data']['value'] as $subfield ) {
					if ( $subfield['type'] === 'data' ) {
						$this->articleSnippet['highlights'][] = $subfield['data'];
					}
				}
			}
		}

		return $this->articleSnippet;
	}
}
