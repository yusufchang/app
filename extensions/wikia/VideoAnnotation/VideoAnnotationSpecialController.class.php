<?php

class VideoAnnotationSpecialController extends WikiaSpecialPageController {

	const DEFAULT_TEMPLATE_ENGINE = WikiaResponse::TEMPLATE_ENGINE_MUSTACHE;

	public function __construct() {
		parent::__construct( 'VideoAnnotation', 'videoannotation', true );
	}

	/**
	 * VideoAnnotation
	 */
	public function index() {
		wfProfileIn( __METHOD__ );

		$this->video = [];

		wfProfileOut( __METHOD__ );
	}

	public function dfxp() {
		wfProfileIn( __METHOD__ );

		$annotation = [];
		$this->annotation = $annotation;

		wfProfileOut( __METHOD__ );
	}
}
