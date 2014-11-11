<?php

class NjordCharacterController extends WikiaController {

	public function index() {
		$params = $this->request->getParams();
		$this->characterModel = !empty( $params['characterModel'] ) ? $params['characterModel'] : null;
		if ( !$this->wg->user->isLoggedIn() ) {
			//TODO: add check for zero state
			return $this->skipRendering();
		}
		$this->wg->out->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/NjordPrototype/css/NjCharacter.scss' ) );

		$this->isAllowedToEdit = $this->wg->user->isAllowed( 'njordeditmode' );
		$wd = new stdClass();
		$wd->title = 'Characters';
		$this->wikiData = $wd;
	}

	public function saveModuleData() {
		$params = $this->request->getParams();


		
	}
}