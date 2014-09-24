<?php

class ApiTemplateSearch extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();
		$query = trim( $params['query'] );
		$results = [];

		if ( strlen( $query ) > 0 ) {
			// Get result for template name exact match
			$templateNameResult = $this->getTemplateNameExactMatch( $query );

			// Get results for article name exact match
			$articleNameResults = $this->getArticleNameExactMatch( $query );

			// Get results for template wildcard search
			if ( strlen( $query ) >= 3 ) {
				$searchResults = $this->getTemplates( $query );
			} else {
				$searchResults = array();
			}

			// Deduplicate results
			if ( $templateNameResult ) {
				$articleNameResults = array_diff( $articleNameResults, [ $templateNameResult ] );
				$searchResults = array_diff( $searchResults, [ $templateNameResult ] );
			}
			$searchResults = array_diff( $searchResults, $articleNameResults );

			// Merge all results
			if ( $templateNameResult ) {
				$results[] = $templateNameResult;
			}
			$results = array_merge( $results, $articleNameResults );
			$results = array_merge( $results, $searchResults );
		}

		$this->getResult()->setIndexedTagName( $results, 'templates' );
		$this->getResult()->addValue( null, 'templates', $results );
	}

	/**
	 * @return string|null
	 */
	private function getTemplateNameExactMatch( $query ) {
		$title = Title::newFromText( $query, NS_TEMPLATE );
		if ( $title && $title->exists() ) {
			return $title->getText();
		}
		return null;
	}

	/**
	 * Gets all templates (if any) user in given article
	 * @return array
	 */
	private function getArticleNameExactMatch( $text ) {
		$results = [];
		$title = $this->getTargetTitle( $text );
		if ( $title ) {
			$templates = $title->getLinksFrom( array(), 'templatelinks', 'tl' );
			if ( count( $templates ) > 0 ) {
				for ( $i = 0; $i < count ( $templates ); $i++ ) {
					if ( !$templates[$i]->isSubpage() ) {
						$results[] = $templates[$i]->getText();
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Gets all templates that are not subpages (for instance Template:Infobox/doc)
	 * and contains $query in its name (wildcard search)
	 * 
	 * @return array
	 */
	private function getTemplates( $query ) {
		$templates = ( new WikiaSQL() )
			->SELECT( 'page_title' )
			->FROM( 'page' )
			->WHERE( 'page_namespace' )->EQUAL_TO( NS_TEMPLATE )
			->AND_('page_title COLLATE LATIN1_GENERAL_CI')->LIKE( '%'.$query.'%' )
			->runLoop( wfGetDB( DB_SLAVE ), function( &$templates, $row ) {
				$title = Title::newFromText( $row->page_title, NS_TEMPLATE );
				// Omit subpages - this information is derived from page title text - having
				// it in the query directly wouldn't be efficient.
				if ( !$title->isSubpage() ) {
					$templates[] = $title->getText();
				}
			} );
		return $templates;
	}

	/**
	 * Get Title based on passed $text and follow redirects if there are any
	 * @return Title|null
	 */
	private function getTargetTitle( $text ) {
		$title = Title::newFromText( $text );
		if ( !$title || !$title->exists() ) {
			return null;
		}
		if ( $title->isRedirect() ) {
			$title = ( new WikiPage( $title ) )->getRedirectTarget();
			if ( !$title || !$title->exists() ) {
				return null;
			}
		}
		return $title;
	}

	public function getAllowedParams() {
		return array(
			'query' => array (
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getVersion() {
		return '$Id$';
	}

}