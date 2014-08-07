<?php

class TimeMachine {

	/** @var  array $data */
	private $data;

	public function __constructor() {
		// Get Time Machine date from cookie, if available, and use it to rewind article to the closest revision
		if ( $_COOKIE['time_machine'] ) {
			$timeMachineData = json_decode( $_COOKIE['time_machine'], true );
			$this->data = $timeMachineData[ F::app()->wg->CityId ];
		}
	}

	/**
	 * @return mixed
	 */
	public function getTimestamp() {
		// Timestamp is the only thing stored here for now, but probably will be more later
		return $this->data;
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
