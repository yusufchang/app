<?php

class VideoAnnotationController extends WikiaController {

	const DEFAULT_TEMPLATE_ENGINE = WikiaResponse::TEMPLATE_ENGINE_MUSTACHE;

	/**
	 * VideoAnnotation
	 */
	public function index() {
		wfProfileIn( __METHOD__ );

		$this->response->addAsset('video_annotation_js');

		//$videoTitle = $this->request->getVal( 'videoTitle', '' );
		$videoTitle = 'IVA_test_4';

		$file = WikiaFileHelper::getVideoFileFromTitle( $videoTitle );
		if ( empty( $file ) ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videohandler-error-video-no-exist' )->text();
			return;
		}

		if ( $file->getProviderName() != 'ooyala' ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videoannotation-error-invalid-provider' )->text();
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

		$videoTitle = 'IVA_test_4';
		//$videoTitle = $this->request->getVal( 'videoTitle', '' );
		$annotation = $this->request->getVal( 'annotation', [] );

		$file = WikiaFileHelper::getVideoFileFromTitle( $videoTitle );
		if ( empty( $file ) ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videohandler-error-video-no-exist' )->text();
			return;
		}

		if ( $file->getProviderName() != 'ooyala' ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videoannotation-error-invalid-provider' )->text();
			return;
		}

		if ( empty( $annotation ) ) {
			$this->result = 'error';
			$this->msg = wfMessage( 'videoannotation-empty-annotation' )->text();
			return;
		}

		$helper = new VideoAnnotationHelper();
		$status = $helper->setAnnotation( $file, $annotation );
		if ( !$status->isGood() ) {
			$this->result = 'error';
			$this->msg = 'Cannot added video annotation.';
			return;
		}

		$this->result = 'ok';
		$this->msg = 'Successfully added video annotation.';

		wfProfileOut( __METHOD__ );
	}

	public function dfxp() {
		wfProfileIn( __METHOD__ );

		$annotation = $this->request->getVal( 'annotation', [] );
		$this->annotation = $annotation;

		wfProfileOut( __METHOD__ );
	}

}
