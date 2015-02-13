<?php

class InfoboxApiController extends WikiaApiController {

	function getData() {
		$pageid = $this->getRequiredParam( "pageid" );
		$title = Title::newFromID($pageid);
		$article = Article::newFromTitle($title, RequestContext::getMain());
		$parserOutput = $article->getParserOutput();
		$this->setVal("items", $parserOutput->getProperty("infoboxes"));
	}
}