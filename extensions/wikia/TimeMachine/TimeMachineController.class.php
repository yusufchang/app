<?php

/**
 * Class TimeMachineController
 */
class TimeMachineController extends WikiaController {

	/**
	 *
	 */
	public function index() {
		$view = $this->getVal( 'view', 'activation' );

		if ( strtolower( $view ) == 'status' ) {
			$tm = new TimeMachine();
			$this->timestamp = $tm->getTimestamp();
			$this->season = $tm->getSeason();
			$this->episode = $tm->getEpisode();

			$content = $this->sendSelfRequest( 'statusBar', [] )->toString();
		} else {
			$content = $this->sendSelfRequest( 'activationBar', [] )->toString();
		}

		$this->content = $content;
		$this->result = "ok";
		$this->msg = '';
	}

	/**
	 * The bar users see when they are using the Time Machine to browse the site
	 */
	public function statusBar() {

	}

	/**
	 * The bar users see when coming from a google search, prompting them to use Time Machine
	 */
	public function activationBar() {

	}
}
