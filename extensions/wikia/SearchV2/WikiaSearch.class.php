<?php

class WikiaSearch extends WikiaObject {

	const RESULTS_PER_PAGE = 10;
	const RESULTS_PER_WIKI = 4;
	const GROUP_RESULTS_SEARCH_LIMIT = 500;
	const GROUP_RESULTS_CACHE_TTL = 900; // 15 mins

	/**
	 * Search client
	 * @var WikiaSearchClient
	 */
	protected $client = null;

	public function __construct( WikiaSearchClient $client ) {
		$this->client = $client;
		parent::__construct();
	}

	/**
	 * perform search
	 *
	 * @param string $query
	 * @param int $page
	 * @param int $length
	 * @param int $cityId
	 * @param string $rankExpr
	 * @param bool $groupResults
	 * @return WikiaSearchResultSet
	 */
	public function doSearch( $query, $page = 1, $length = null, $cityId = 0, $rankExpr = '', $groupResults = false ) {
		$length = !empty($length) ? $length : self::RESULTS_PER_PAGE;
		$groupResults = ( empty($cityId) && $groupResults );

		if($groupResults) {
			// check cache first
			$results = $this->getGroupResultsFromCache($query, $rankExpr);
			if(empty($results)) {
				$results = $this->client->search( $query, 0, self::GROUP_RESULTS_SEARCH_LIMIT, $cityId, $rankExpr );
				$results = $this->groupResultsPerWiki( $results );

				$this->setGroupResultsToCahce( $query, $rankExpr, $results );
			}
			$results->setCurrentPage($page);
			$results->setResultsPerPage($length);
		}
		else {
			// no grouping, e.g. intra-wiki searching
			$results = $this->client->search( $query, ( ($page - 1) * $length ), $length, $cityId, $rankExpr );
		}

		return $results;
	}

	private function getGroupResultsFromCache($query, $rankExpr) {
		return $this->wg->Memc->get( $this->getGroupResultsCacheKey($query, $rankExpr) );
	}

	private function setGroupResultsToCahce($query, $rankExpr, WikiaSearchResultSet $resultSet) {
		$this->wg->Memc->set( $this->getGroupResultsCacheKey($query, $rankExpr), $resultSet, self::GROUP_RESULTS_CACHE_TTL );
	}

	private function getGroupResultsCacheKey($query, $rankExpr) {
		return $this->wf->SharedMemcKey( 'WikiaSearchResultSet', md5($query.$rankExpr) );
	}

	private function groupResultsPerWiki(WikiaSearchResultSet $results) {
		$wikiResults = array();

		foreach($results as $result) {
			if($result instanceof WikiaSearchResult) {
				$cityId = $result->getCityId();
				if(!isset($wikiResults[$cityId])) {
					$wikiResultSet = F::build( 'WikiaSearchResultSet' );
					$wikiResultSet->setHeader('cityTitle', WikiFactory::getVarValueByName( 'wgSitename', $cityId ));
					$wikiResultSet->setHeader('cityUrl', WikiFactory::getVarValueByName( 'wgServer', $cityId ));
					$wikiResultSet->setHeader('cityArticlesNum', $result->getVar('cityArticlesNum', false));

					$wikiResults[$cityId] = $wikiResultSet;
				}
				$set = $wikiResults[$cityId];
				if($set->getResultsNum() < self::RESULTS_PER_WIKI) {
					$set->addResult($result);
				}
				$set->incrResultsFound();
			}
		}

		return F::build( 'WikiaSearchResultSet', array( 'results' => $wikiResults, 'resultsFound' => $results->getResultsFound(), 'resultsStart' => $results->getResultsStart(), 'isComplete' => $results->isComplete() ) );
	}

	private function groupWikiResults($wikiId, Array $resultsPerWiki) {
		return $resultsPerWiki;
	}

	public function setClient( WikiaSearchClient $client ) {
		$this->client = $client;
	}

	public function getPage( $pageId, $withMetaData = true ) {
		$result = array();

		$page = F::build( 'Article', array( $pageId ), 'newFromID' );

		if(!($page instanceof Article)) {
			throw new WikiaException('Invalid Article ID');
		}

		// hack: setting wgTitle as rendering fails otherwise
		$wgTitle = $this->wg->Title;
		$this->wg->Title = $page->getTitle();

		// hack: setting action=render to exclude "Related Pages" and other unwanted stuff
		$wgRequest = $this->wg->Request;
		$this->wg->Request->setVal('action', 'render');

		if( $page->isRedirect() ) {
			$redirectPage = F::build( 'Article', array( $page->getRedirectTarget() ) );
			$redirectPage->loadContent();

			// hack: setting wgTitle as rendering fails otherwise
			$this->wg->Title = $page->getRedirectTarget();

			$redirectPage->render();
			$canonical = $page->getRedirectTarget()->getPrefixedText();
		}
		else {
			$page->render();
			$canonical = '';
		}

		$result['sitename'] = $this->wg->Sitename;
		$result['title'] = $page->getTitle()->getText();
		$result['canonical'] = $canonical;
		$result['text'] = $this->wg->Out->getHTML();
		$result['url'] = $page->getTitle()->getFullUrl();
		$result['ns'] = $page->getTitle()->getNamespace();

		if( $withMetaData ) {
			$result['metadata'] = $this->getPageMetaData( $page );
		}

		// restore global state
		$this->wg->Title = $wgTitle;
		$this->wg->Request = $wgRequest;

		return $result;
	}

	public function getPageMetaData( $page ) {
		$result = array();

		$data = $this->callMediaWikiAPI( array(
			'titles' => $page->getTitle(),
			'bltitle' => $page->getTitle(),
			'action' => 'query',
			'list' => 'backlinks',
			'bllimit' => 600
		));

		if( is_array( $data['query']['backlinks'] ) ) {
			$result['backlinks'] = count( $data['query']['backlinks'] );
		}
		else {
			$result['backlinks'] = 0;
		}

		/*
		$data = $this->callMediaWikiAPI( array(
			'pageids' => $page->getId(),
			'action' => 'query',
			'prop' => 'info',
			'inprop' => 'url|created|views|revcount|redirect'
		));

		if( isset( $data['query']['pages'][$page->getId()] ) ) {
			$pageData = $data['query']['pages'][$page->getId()];
			$result['views'] = $pageData['views'];
			$result['revcount'] = $pageData['revcount'];
			$result['created'] = $pageData['created'];
			$result['touched'] = $pageData['touched'];
		}
		*/
		$result['views'] = 1;

		return $result;
	}

	private function callMediaWikiAPI( Array $params ) {
		$api = F::build( 'ApiMain', array( 'request' => new FauxRequest($params) ) );
		$api->execute();

		return  $api->getResultData();
	}

}