<?php

class TimeMachine {

	/** @var  array $data */
	private $data;

	public function __constructor() {
		// Get Time Machine date from cookie, if available, and use it to rewind article to the closest revision
		if ( $_COOKIE['time_machine'] ) {
			// Translate our city ID into the subdomain for this wikia
			$dbname = WikiFactory::IDtoDB( F::app()->wg->CityId );
			$domain = WikiFactory::DBtoDomain( $dbname );
			$parts = explode('.', $domain);

			$timeMachineData = json_decode( $_COOKIE['time_machine'], true );
			$this->data = $timeMachineData[ $parts[0] ];
		}
	}

	/**
	 * @return mixed
	 */
	public function getTimestamp() {
		return $this->data['timestamp'];
	}

	public function getSeason() {
		return $this->data['season'];
	}

	public function getEpisode() {
		return $this->data['episode'];
	}

	/**
	 * @param Title $title
	 *
	 * @return int
	 */
	public function getRevId( $title = null ) {
		if ( empty( $title ) ) {
			$title = F::app()->wg->Title;
		}
		$rev = Revision::getLatestBeforeTimestamp( $title, $this->getTimestamp() );

		if ( empty( $rev ) ) {
			return null;
		} else {
			$rev->getId();
		}
	}
}
