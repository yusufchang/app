<?php

/**
 * Class InsightsCuratedMainPageModel
 */
class InsightsCuratedMainPageModel extends InsightsPageModel {
	const INSIGHT_TYPE = 'curatedmainpage';

	public function getInsightCacheParams() {
		return;
	}

	public function initModel( $params ) {
	}

	public function getContent( $params ) {
		global $wgWikiaCuratedContent;

		return [
			'isCuratedContentEnabled' => !empty( $wgWikiaCuratedContent )
		];
	}

	public function getInsightType() {
		return self::INSIGHT_TYPE;
	}
}
