<?php

/**
 * Provides an API for tracking page views on Lyrics Wiki
 */
class LyricFindTrackingService extends WikiaService {

	// LyricFind API response codes
	const CODE_LYRIC_IS_AVAILABLE = 101;
	const CODE_LYRIC_IS_INSTRUMENTAL = 102;
	const CODE_LRC_IS_AVAILABLE  = 111;
	const CODE_LYRIC_IS_BLOCKED  = 206;

	// Not documented. The response body says "SUCCESS: NO LYRICS" which I assume means that they
	// have licensing in place, they just don't have lyrics for the song.
	const CODE_SUCCESS_NO_LYRICS = 106;

	const DEFAULT_USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.142 Safari/535.19';

	const LOG_GROUP = 'lyricfind-tracking';

	/**
	 * Marks given page with lyric for removal
	 *
	 * @param $pageId int article ID
	 * @return bool result
	 */
	private function markLyricForRemoval($pageId) {
		$this->wf->SetWikiaPageProp(WPP_LYRICFIND_MARKED_FOR_REMOVAL, $pageId, 1);

		self::log(__METHOD__, "marked page #{$pageId} for removal");
		return true;
	}

	/**
	 * Sometimes pages that were marked for removal, may no longer be banned (for instance,
	 * a licensing deal was reached with rights-owners that didn't have an agreement previously).
	 * In these cases, this function will change the page-property so that the page is no-longer
	 * hidden.
	 *
	 * @param $pageId int article ID
	 * @return bool result
	 */
	private function markLyricAsNotRemoved($pageId) {
		$this->wf->SetWikiaPageProp(WPP_LYRICFIND_MARKED_FOR_REMOVAL, $pageId, 0);

		self::log(__METHOD__, "marked page #{$pageId} as no longer removed");
		return true;
	}

	/**
	 * Artist and track name needs to be lowercase and without commas or colons
	 *
	 * @param $item string parameter value to be encoded
	 * @return string encoded value
	 */
	private static function encodeParam($item) {
		return mb_strtolower(strtr($item, [
			',' => ' ',
			':' => ' ',
		]));
	}

	/**
	 * Returns properly formatted "trackid" parameter for LyricFind API from given data
	 *
	 * Example: trackid=amg:2033,gnlyricid:123,trackname:mony+mony,artistname:tommy+james
	 *
	 * @param $data array containing amgid, gnlyricid and title of the lyric
	 */
	public function formatTrackId($data) {
		$parts = [];

		if (!empty($data['amg'])) {
			$parts[] = sprintf('amg:%d', $data['amg']);
		}

		if (!empty($data['gracenote'])) {
			$parts[] = sprintf('gnlyricid:%d', $data['gracenote']);
		}

		if(strpos($data['title'], ':') === false){
			$artistName = $data['title'];
			$trackName = "";
		} else {
			list($artistName, $trackName) = explode(':', $data['title'], 2);
		}

		$parts[] = sprintf('trackname:%s', self::encodeParam($trackName));
		$parts[] = sprintf('artistname:%s', self::encodeParam($artistName));

		return join(',', $parts);
	}

	/**
	 * @param $amgId int|bool AMG (All Music Guide) lyric ID to track page view for (or false if not found)
	 * @param $gracenoteId int|bool Gracenote lyric ID to track page view for (or false if not found)
	 * @param $title Title page with the lyric to track
	 * @return Status success
	 */
	public function track($amgId, $gracenoteId, Title $title) {
		wfProfileIn(__METHOD__);

		$status = Status::newGood();

		// format trackid parameter
		$trackId = $this->formatTrackId([
			'amg' => $amgId,
			'gracenote' => $gracenoteId,
			'title' => $title->getText()
		]);

		$url = $this->wg->LyricFindApiUrl . '/lyric.do';
		$data = [
			'apikey' => $this->wg->LyricFindApiKeys['display'],
			'reqtype' => 'offlineviews',
			'count' => 1,
			'trackid' => $trackId,
			'output' => 'json',
			'useragent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : self::DEFAULT_USER_AGENT
		];

		wfDebug(__METHOD__ . ': ' . json_encode($data) . "\n");

		$resp = Http::post($url, ['postData' => $data]);

		if ($resp !== false) {
			wfDebug(__METHOD__ . ": API response - {$resp}\n");
		}

		// get the code from API response
		if ($resp !== false) {
			$json = json_decode($resp, true);

			$code = !empty($json['response']['code']) ? intval($json['response']['code']) : false;
			$status->value = $code;

			switch ($code) {
				case self::CODE_LYRIC_IS_BLOCKED:
					$this->markLyricForRemoval($this->wg->Title->getArticleID());
					break;

				case self::CODE_SUCCESS_NO_LYRICS:
				case self::CODE_LRC_IS_AVAILABLE:
				case self::CODE_LYRIC_IS_INSTRUMENTAL:
				case self::CODE_LYRIC_IS_AVAILABLE:
					// LyricFind has reported that the page is okay. If it was banned before, unban it.
					$removedProp = $this->wf->GetWikiaPageProp(WPP_LYRICFIND_MARKED_FOR_REMOVAL, $this->wg->Title->getArticleID());
					$isMarkedAsRemoved = (!empty($removedProp));
					if($isMarkedAsRemoved){
						$this->markLyricAsNotRemoved($this->wg->Title->getArticleID());
					}
					break;

				default:
					$status->fatal('not expected response code');
					self::log(__METHOD__, "got #{$code} response code from API (track amg#{$amgId} / gn#{$gracenoteId} / '{$title->getPrefixedText()}')");
			}
		}
		else {
			$status = Status::newFatal("API request failed!");
			self::log(__METHOD__, "LyricFind API request failed!");
		}

		wfProfileOut(__METHOD__);
		return $status;
	}
	
	/**
	 * WARNING: This function makes an API request and is therefore slow.
	 *
	 * This function is intended only for use to test if not-yet-created pages are blocked
	 * (so that we can prevent their creation). If calling-code just needs to check if an
	 * already-existing page is blocked, check the page properties using
	 * GetWikiaPageProp(WPP_LYRICFIND_MARKED_FOR_REMOVAL) instead.
	 */
	public static function isPageBlockedViaApi($amgId="", $gracenoteId="", $pageTitleText=""){
		wfProfileIn(__METHOD__);
		
		$isBlocked = false;
		
		$app = F::app();
		$service = new LyricFindTrackingService();

		// format trackid parameter
		$trackId = $service->formatTrackId([
			'amg' => $amgId,
			'gracenote' => $gracenoteId,
			'title' => $pageTitleText
		]);

		$url = $app->wg->LyricFindApiUrl . '/lyric.do';
		$data = [
			'apikey' => $app->wg->LyricFindApiKeys['display'],
			'reqtype' => 'offlineviews',
			'count' => 1, // This is overcounting since we know it's not a view (page doesn't exist yet), but their API won't accept "0"
			'trackid' => $trackId,
			'output' => 'json',
			'useragent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : self::DEFAULT_USER_AGENT
		];

		wfDebug(__METHOD__ . ': ' . json_encode($data) . "\n");

		$resp = Http::post($url, ['postData' => $data]);

		if ($resp !== false) {
			wfDebug(__METHOD__ . ": API response - {$resp}\n");
		}

		// get the code from API response
		if ($resp !== false) {
			$json = json_decode($resp, true);

			$code = !empty($json['response']['code']) ? intval($json['response']['code']) : false;

			switch ($code) {
				case self::CODE_LYRIC_IS_BLOCKED:
					$isBlocked = true;
					break;

				case self::CODE_SUCCESS_NO_LYRICS:
				case self::CODE_LRC_IS_AVAILABLE:
				case self::CODE_LYRIC_IS_INSTRUMENTAL:
				case self::CODE_LYRIC_IS_AVAILABLE:
					break;

				default:
					self::log(__METHOD__, "got #{$code} response code from API (track amg#{$amgId} / gn#{$gracenoteId} / '{$pageTitleText}')");
			}
		} else {
			self::log(__METHOD__, "LyricFind API request failed in isPageBlockedViaApi()!");
		}

		wfProfileOut(__METHOD__);
		return $isBlocked;
	}

	/**
	 * Log to /var/log/private file
	 *
	 * @param $method string method
	 * @param $msg string message to log
	 */
	private static function log($method, $msg) {
		Wikia::log(self::LOG_GROUP . '-WIKIA', false, $method . ': ' . $msg, true /* $force */);
	}
}

