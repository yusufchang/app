<?php
class SpecialTrendingController extends WikiaSpecialPageController {
	public function __construct() {
        parent::__construct('Trending', 'trending', true);
	}

	/**
	 * Main page for Special:Css page
	 *
	 * @return boolean
	 */
	public function index() {
		wfProfileIn(__METHOD__);

		if ( $this->wg->User->isBlocked() ) {
			$block = $this->wg->User->mBlock;
			wfProfileOut(__METHOD__);
			throw new UserBlockedError( $block );
		}

		$this->handleAssets();
		$this->wg->Out->setPageTitle( $this->wf->Message('special-trending-title')->plain() );

		$this->getTrending();
		$this->getLoosers();

		$this->wg->SuppressSpotlights = true;

		wfProfileOut(__METHOD__);
		return true;
	}

	protected function handleAssets() {
		$this->response->addAsset('special_trending_styles');
		$this->response->addAsset('special_trending_scripts');
	}

	protected function getTrending() {
		global $wgCityId;

		$this->trendingArticles = $this->sendRequest('ArticlesApi', 'getTrending',
			[
				'wikiId' => $wgCityId,
				'namespaces' => 0
			])->getData()['items'];
	}

	protected function getLoosers() {
		global $wgCityId;

		$this->loosingArticles = $this->sendRequest('ArticlesApi', 'getTrending',
			[
				'wikiId' => $wgCityId,
				'namespaces' => 0,
				'order' => 'asc'
			])->getData()['items'];
	}
}
