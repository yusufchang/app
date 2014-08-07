<?php

/**
 * Class TimeMachineController
 */
class TimeMachineController extends WikiaController {

	/**
	 *
	 */
	public function index() {
		gbug("Time machine controller");

		$this->message = 'This is your Time Machine';
		$this->result = "ok";
		$this->msg = '';
	}
}
