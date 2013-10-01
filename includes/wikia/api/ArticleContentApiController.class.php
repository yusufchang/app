<?php

class ArticleContentApiController extends WikiaApiController {

	public function test() {
		$id = $this->request->getVal( 'id' );
		$service = new ArticleService( $id );
		$result = $service->getWikiText();

		$this->response->setVal( 'data', $result );
	}

}
