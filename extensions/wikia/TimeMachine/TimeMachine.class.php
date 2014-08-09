<?php

class TimeMachine {

	/** @var  array $data */
	private $data;
	private $subdomain;

	public function __construct() {
		// Look for the subdomain we were requested from
		$server_parts = explode( '.', $_SERVER[ 'SERVER_NAME' ] );
		$this->subdomain = $server_parts[0];

		// Get Time Machine date from cookie, if available, and use it to rewind article to the closest revision
		if ( $_COOKIE[ 'time_machine' ] ) {
			$timeMachineData = json_decode( $_COOKIE[ 'time_machine' ], true );

			$this->data = $timeMachineData[ $this->subdomain ];
		}
	}

	public function isActive() {
		// If we don't have any data for this wiki, it means its not active
		return empty( $this->data ) ? false : true;
	}

	public function isInactive() {
		return ! $this->isActive();
	}

	public function getSubdomain() {
		return $this->subdomain;
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
	 * @return null|int
	 */
	public function getRevId( $title = null ) {
		// Use current title if one is not given
		if ( empty( $title ) ) {
			$title = F::app()->wg->Title;
		}

		// Make sure we have a timestamp
		$ts = $this->getTimestamp();
		if ( empty( $ts ) ) {
			return null;
		}

		// See if we have a revision prior to this timestamp
		$rev = Revision::getLatestBeforeTimestamp( $title, $ts );
		if ( empty( $rev ) ) {
			return null;
		} else {
			return $rev->getId();
		}
	}
}
