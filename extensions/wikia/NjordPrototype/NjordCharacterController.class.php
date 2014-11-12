<?php

class NjordCharacterController extends WikiaController {

	public function index() {
		$characterModel = new CharacterModuleModel( Title::newMainPage()->getText() );
		$characterModel->getFromProps();
		$this->characterModel = $characterModel;
		$this->characterModel = new CharacterModuleModel( Title::newMainPage()->getText() );
		$this->characterModel->setFromContent('Category:TV series|Image:Mainpageportal1.png|Series and movies|Description 2
Category:Media|Image:Mainpageportal2.png|Media|Description 3
Category:In-universe articles|Image:Mainpageportal3.png|Around the universe|Description 4');
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