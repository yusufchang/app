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

		if( $this->checkPermissions() ) {
			$this->displayRestrictionError();
			wfProfileOut(__METHOD__);
			return false; // skip rendering
		}

		if ( $this->wg->User->isBlocked() ) {
			$block = $this->wg->User->mBlock;
			wfProfileOut(__METHOD__);
			throw new UserBlockedError( $block );
		}

		$this->handleAssets();
		$this->wg->Out->setPageTitle( $this->wf->Message('special-treding-title')->plain() );

		$this->wg->SuppressSpotlights = true;

		wfProfileOut(__METHOD__);
		return true;
	}

	protected function handleAssets() {
		$this->response->addAsset('/extensions/wikia/SpecialTrending/css/main.scss');
		$this->response->addAsset('special_trending_js');
	}
}
