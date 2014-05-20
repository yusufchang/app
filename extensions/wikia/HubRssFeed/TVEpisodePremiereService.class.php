<?php
class TVEpisodePremiereService extends WikiaService {

	const tvrage_rss_yesterday = "http://www.tvrage.com/myrss.php?class=scripted&date=yesterday";
	const MIN_ARTICLE_QUALITY = 30;

	/* @var Solarium_Client $solariumClient  */
	protected $solariumClient = null;

	private function getSolariumClient() {
		if (empty($this->solariumClient)) {
		$config = (new Wikia\Search\QueryService\Factory)->getSolariumClientConfig();
		$this->solariumClient = new Solarium_Client($config);
		}
		return $this->solariumClient;
	}

	private function parseTitle($episodeRssTitle) {
		$episodeRssTitle = str_replace("- ", "", trim($episodeRssTitle));
		$titleArr = explode(" (", $episodeRssTitle);
		$parsed = array(
			"title" => $titleArr[0],
			"series" => "",
			"episode" => ""
		);
		if ( count($titleArr) > 1) {
			$epData = explode("x", trim($titleArr[1], ")"));
			$parsed['series'] = $epData[0];
			$parsed['episode'] = $epData[1];
		}
		return $parsed;
	}

	public function getTVEpisodes() {
		$data = simplexml_load_file( self::tvrage_rss_yesterday) ;
		$elems = $data->children();
		$episodes = [];
		foreach ($elems->children() as $elem) {
			if (!empty($elem->title)) {
				$EData = $this->parseTitle((string)$elem->title);
				$EData['episode_title'] = (string)$elem->description;
				if (!empty($EData['episode_title'])) {
					$episodes[] = $EData;
				}
			}
		}
		return $episodes;
	}

	public function getWikiaArticles( $episodes ) {

		foreach ( $episodes as $i => $episode ) {
			$requestData = array(
				'seriesName' => $episode['title'],
				'episodeName' => $episode['episode_title'],
				'minArticleQuality' => self::MIN_ARTICLE_QUALITY
			);
			try {
				$response = $this->sendRequest('TvApiController', 'getEpisode', $requestData );
				$episodes[$i]['wikia'] = $response->getData();
			} catch (Exception $e) {

			}
		}
		return $episodes;
	}
}


