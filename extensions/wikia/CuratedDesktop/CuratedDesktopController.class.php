<?php

class CuratedDesktopController extends WikiaController {

	function index() {
		global $wgWikiaCuratedContent;
//		$this->response->setTemplateEngine( WikiaResponse::TEMPLATE_ENGINE_HANDLEBARS );
		$this->response->addAsset( 'extensions/wikia/CuratedDesktop/styles/CuratedDesktop.scss' );
		$this->response->setVal('debug', $wgWikiaCuratedContent);
//		$this->response->setData( $this->getTestData() );
	}
}
