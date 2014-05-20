<?php

class HubRssFeedSpecialController extends WikiaSpecialPageController {
	const SPECIAL_NAME = 'HubRssFeed';
	const CACHE_KEY = 'HubRssFeed';
	const CACHE_TIME = 3600;
	/** Use it after release to generate new memcache keys. */
	const CACHE_BUST = 9;

	protected $hubs = [
		'gaming' => WikiFactoryHub::CATEGORY_ID_GAMING,
		'entertainment' => WikiFactoryHub::CATEGORY_ID_ENTERTAINMENT,
		'lifestyle' => WikiFactoryHub::CATEGORY_ID_LIFESTYLE
	];

	protected $customFeeds = [
		'tv' => 'customRssTV'
	];

	/**
	 * @var HubRssFeedModel
	 */
	protected $model;

	/**
	 * @var Title
	 */
	protected $currentTitle;

	/**
	 * @param \HubRssFeedModel $model
	 */
	public function setModel( $model ) {
		$this->model = $model;
	}

	/**
	 * @return \HubRssFeedModel
	 */
	public function getModel() {
		return $this->model;
	}


	public function __construct() {
		parent::__construct( self::SPECIAL_NAME, self::SPECIAL_NAME, false );
		$this->currentTitle = SpecialPage::getTitleFor( self::SPECIAL_NAME );
	}


	public function notfound() {
		$url = $this->currentTitle->getFullUrl();
		$links = [];

		foreach ( $this->hubs as $k => $v ) {
			$links[ ] = $url . '/' . ucfirst( $k );
		}

		$this->setVal( 'links', $links );
		$this->wg->SupressPageSubtitle = true;

	}


	public function index() {
		global $wgHubRssFeedCityIds;

		$params = $this->request->getParams();

		$hubName = strtolower( (string)$params[ 'par' ] );

		if ( !isset( $this->hubs[ $hubName ] ) ) {
			if ( isset( $this->customFeeds[$hubName] ) ) {
				return $this->forward( 'HubRssFeedSpecial', $this->customFeeds[$hubName] );
			}
			return $this->forward( 'HubRssFeedSpecial', 'notfound' );
		}

		$langCode = $this->app->wg->ContLang->getCode();
		$this->model = new HubRssFeedModel($langCode);

		$memcKey = wfMemcKey( self::CACHE_KEY, $hubName, self::CACHE_BUST, $langCode );

		$xml = $this->wg->memc->get( $memcKey );
		if ( $xml === false ) {
			$service = new HubRssFeedService($langCode, $this->currentTitle->getFullUrl() . '/' . ucfirst( $hubName ));
			$verticalId = $this->hubs[ $hubName ];
			$cityId = isset( $wgHubRssFeedCityIds[ $hubName ] ) ? $wgHubRssFeedCityIds[ $hubName ] : 0;
			$data = array_merge( $this->model->getRealDataV3( $cityId ), $this->model->getRealDataV2( $verticalId ) );
			$xml = $service->dataToXml( $data, $verticalId );
			$this->wg->memc->set( $memcKey, $xml, self::CACHE_TIME );
		}

		$this->response->setFormat( WikiaResponse::FORMAT_RAW );
		$this->response->setBody( $xml );
		$this->response->setContentType( 'text/xml' );
	}

	public function customRssTV() {

		$body = $this->wg->memc->get("test-rss-tv2");
		if ( 0 && $body ) {
			$this->response->setFormat( WikiaResponse::FORMAT_RAW );
			$this->response->setBody( $body );
			$this->response->setContentType( 'text/xml' );
		} else {

			$rssService = new RssFeedService();
			$tvPremieres = new TVEpisodePremiereService();

			$rssService->setFeedTitle("Wikia TV");
			$rssService->setFeedDescription("Tv episodes");
			$rssService->setFeedUrl("http://www.wikia.com/Special:HubRssFeed/Tv");
			$epizodes = $tvPremieres->getTVEpisodes();
			$data = $tvPremieres->getWikiaArticles( $epizodes );

			$wikisFound = [];
			foreach ( $data as $ep ) {
				if ( isset($ep['wikia']) ) {
					$wikisFound[$ep['wikia']['wikiId']] = $ep['wikia']['wikiId'];
				}
			}
			$d = $tvPremieres->otherArticles($wikisFound[0]);
			var_dump($d);
			var_dump(($wikisFound)); die;

			foreach ( $data as $ep ) {
				if ( isset( $ep['wikia'] ) ) {

					$details = $rssService->getArticleDetails(
						$ep['wikia']['wikiId'],
						$ep['wikia']['articleId'],
						$ep['wikia']['url']
					);
					$abstract = $details->items->{$ep['wikia']['articleId']}->abstract;
					$timestamp = $details->items->{$ep['wikia']['articleId']}->revision->timestamp;
					$thumb = $details->items->{$ep['wikia']['articleId']}->thumbnail;
					$thumbA = null;
					if ( !empty($thumb) ) {
						$thumbA['url'] = $thumb;
						$thumbA['width'] = $details->items->{$ep['wikia']['articleId']}->original_dimensions->width;
						$thumbA['height'] = $details->items->{$ep['wikia']['articleId']}->original_dimensions->height;
					}
					if ( $details ) {
						$rssService->addElem(
							"New episode from " . $ep['title'].': '.$ep['episode_title'],
							$abstract,
							str_replace("jacek.wikia-dev", "wikia", $ep['wikia']['url']),
							$timestamp,
							$thumbA
						);
					}
				}
			}

			$body = $rssService->toXml();
			$this->wg->memc->set("test-rss-tv2", $body);

			$this->response->setFormat( WikiaResponse::FORMAT_RAW );
			$this->response->setBody( $body );
			$this->response->setContentType( 'text/xml' );
		}
	}

}
