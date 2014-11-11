<?php

class NjordCharacterController extends WikiaController {

	public function index() {
		$params = $this->request->getParams();
		$this->characterModel = !empty( $params['characterModel'] ) ? $params['characterModel'] : null;
	}
}