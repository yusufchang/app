<?php
/**
 * @author ADi
 * @author Jacek Jursza
 */

class SDSVideoMetadataController extends WikiaSpecialPageController {

	public function __construct() {
		parent::__construct('VMD');
	}

	public function index() {

		$this->response->addAsset('extensions/wikia/SDSVideoMetadata/css/SDSVideoMetadata.scss');
		$this->response->addAsset('extensions/wikia/SDSVideoMetadata/js/formUIHelpers.SDSVideoMetadata.js');
		$file = $this->getVal('video');


		$fileTitle = Title::newFromText( $file );
		$fileObject = wfFindFile( $fileTitle );
		if ( empty( $fileObject ) ) {
			$this->setVal( 'isCorrectFile', false );
			return false;
		} else {
			if($this->request->wasPosted()) {
				$isCompleted = (bool) $this->request->getVal('vcCompleted', false);
				$this->setFileCompleted( $fileTitle, $isCompleted );

				$requestParams = $this->getRequest()->getParams();
				// var_dump( $requestParams );
				$connector = new VideoClipGamingVideo();
				$pandorka = $connector->newPandoraSDSObjectFromFormData( $requestParams );
				echo '<pre>';
				echo PandoraJsonLD::toJsonLD( $pandorka );
				print_r( $pandorka );

			}

			$this->setVal( 'isCorrectFile', true );
			$this->setVal( 'isCompleted', $this->getFileCompleted( $fileTitle ) );
		}

		$this->setVal('file', $file);
	}

	/**
	 * set "completed" flag for given file
	 *
	 * @todo move this to model class when it will be ready
	 *
	 * @param Title $fileTitle
	 * @param bool $isCompleted
	 */
	private function setFileCompleted(Title $fileTitle, $isCompleted = true) {
		wfSetWikiaPageProp( WPP_VIDEO_METADATA_COMPLETED, $fileTitle->getArticleID(), $isCompleted );
	}

	/**
	 * get "completed" flag for given file
	 *
	 * @todo move this to model class when it will be ready
	 *
	 * @param Title $fileTitle
	 */
	private function getFileCompleted(Title $fileTitle) {
		return wfGetWikiaPageProp( WPP_VIDEO_METADATA_COMPLETED, $fileTitle->getArticleID() );
	}

}
