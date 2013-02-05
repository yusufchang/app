<?php

class PandoraResponse {
	/**
	 * @param $status Status
	 * @param $response String
	 */
	public function __construct($status, $response) {
		$this->status = $status;
		$this->response = $response;
	}

	public function isOK() {
		return $this->status->isOK();
	}

	public function getMessage() {
		return $this->status->getMessage();
	}

	private $jsonResponse = null;

	public function asJson() {
		if ( is_null( $this->jsonResponse ) ) {
			$this->jsonResponse = json_decode($this->response);
		}
		return $this->jsonResponse;
	}

}