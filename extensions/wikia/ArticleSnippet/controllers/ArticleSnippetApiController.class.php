<?php

class ArticleSnippetApiController extends WikiaApiController {

	const DATA_FIELDS_LIMIT = 5;

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

		$highlightedFields = $regularFields = [];

		foreach ( $data[0]['data'] as $field ) {
			if ( $field['type'] === 'title' ) {
				$this->articleSnippet['title'] = $this->stripTags( $field['data']['value'] );

			} elseif ( $field['type'] === 'image' ) {
				$this->articleSnippet['image'] = $field['data'][0]['url'];

			} elseif ( $field['type'] === 'data' ) {
				if ( isset( $field['data']['highlight'] ) ) {
					$highlightedFields[] = array_map( [ new self(), 'stripTags' ], $field['data'] );
				} else {
					$regularFields[] = array_map( [ new self(), 'stripTags' ], $field['data'] );
				}
			} elseif ( $field['type'] === 'group' ) {
				foreach ( $field['data']['value'] as $subfield ) {
					if ( $subfield['type'] === 'data' ) {
						if ( isset( $subfield['data']['highlight'] ) ) {
							$highlightedFields[] = array_map( [ new self(), 'stripTags' ], $subfield['data'] );
						} else {
							$regularFields[] = array_map( [ new self(), 'stripTags' ], $subfield['data'] );
						}
					}
				}
			}
		}

		$highlightedFieldsCount = count( $highlightedFields );

		if ( $highlightedFieldsCount > 1 ) {
			usort( $highlightedFields, function ( $a, $b ) {
				return (int)$a['highlight'] - (int)$b['highlight'];
			} );
		}

		if ( $highlightedFieldsCount < self::DATA_FIELDS_LIMIT ) {
			$diff = self::DATA_FIELDS_LIMIT - $highlightedFieldsCount;
			$highlightedFields = array_merge( $highlightedFields, array_slice( $regularFields, 0, $diff ) );
		}

		$this->articleSnippet['highlights'] = $highlightedFields;

		return $this->articleSnippet;
	}

	public function stripTags( $string ) {
		$string = preg_replace( '/<br\s?\/?>/', ', ', $string );
		return strip_tags($string);
	}
}
