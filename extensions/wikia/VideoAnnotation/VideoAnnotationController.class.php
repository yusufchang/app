<?php

class VideoAnnotationController extends WikiaController {

	const DEFAULT_TEMPLATE_ENGINE = WikiaResponse::TEMPLATE_ENGINE_MUSTACHE;

	/**
	 * VideoAnnotation
	 */
	public function index() {
		wfProfileIn( __METHOD__ );

		$this->response->addAsset('video_annotation_js');

		$videoTitle = $this->request->getVal( 'videoTitle', '' );

		$file = WikiaFileHelper::getVideoFileFromTitle( $videoTitle );
		if ( empty( $file ) ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videohandler-error-video-no-exist' )->text();
			return;
		}

		$helper = new VideoAnnotationHelper();
		$annotation = $helper->getAnnotation( $file );

		$this->result = 'ok';
		$this->annotation = $annotation;

		wfProfileOut( __METHOD__ );
	}

	public function save() {
		wfProfileIn( __METHOD__ );

		$videoTitle = $this->request->getVal( 'videoTitle', '' );
		$annotation = $this->request->getVal( 'annotation', [] );

		if ( !$this->wg->User->isLoggedIn() ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videos-error-not-logged-in' )->text();
			return;
		}

		if ( !$this->wg->User->isAllowed( 'videoannotation' ) ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videoannotation-error-permission' )->plain();
			return;
		}

		$file = WikiaFileHelper::getVideoFileFromTitle( $videoTitle );
		if ( empty( $file ) ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videohandler-error-video-no-exist' )->text();
			return;
		}

		if ( !VideoAnnotationHelper::isValidProvider( $file ) ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videoannotation-error-invalid-provider' )->text();
			return;
		}

		$helper = new VideoAnnotationHelper();
		if ( empty( $annotation ) ) {
			$status = $helper->deleteAnnotation( $file );
		} else {
			$status = $helper->setAnnotation( $file, $annotation );
		}

		if ( !$status ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videoannotation-error-edited' )->text();
			return;
		}

		$this->result = 'ok';
		$this->msg = wfMessage( 'videoannotation-success-edited' )->text();

		wfProfileOut( __METHOD__ );
	}

	public function dfxp() {
		wfProfileIn( __METHOD__ );

		$annotation = $this->request->getVal( 'annotation', [] );
		$this->annotation = $annotation;

		wfProfileOut( __METHOD__ );
	}

}
