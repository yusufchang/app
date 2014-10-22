<?php

abstract class HubOnlyRssModel extends BaseRssModel {

	const MAX_NUM_ITEMS_IN_FEED = 15;

	protected function loadData( $lastTimestamp, $duplicates ) {
		$rawData = $this->getRawDataFromHubs( $lastTimestamp, $duplicates );
		return $this->finalizeRecords( $rawData, static::getFeedName() );
	}

	protected function formatTitle( $item ) {
		return $item;
	}

	protected abstract function getHubCityIds();

	/**
	 * @param $lastTimestamp
	 * @param $duplicates
	 * @return array
	 */
	protected function getRawDataFromHubs( $lastTimestamp, $duplicates ) {
		$rawData = [ ];
		foreach ( $this->getHubCityIds() as $hubCityId ) {
			$rawData = $this->getDataFromHubs( $hubCityId, $lastTimestamp );
			$deduplicatedData = $this->removeDuplicates( $rawData, $duplicates );
			$hubFeedData = $this->findIdForUrls( $deduplicatedData, self::SOURCE_HUB . '_' . $hubCityId );
			$rawData = array_merge( $rawData, $hubFeedData );
		}
		return $rawData;
	}
}
