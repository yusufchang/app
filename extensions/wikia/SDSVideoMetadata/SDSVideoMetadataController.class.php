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
				$this->setVal( 'wasPasted', true );
				$isCompleted = (bool) $this->request->getVal('vcCompleted', false);
				$this->setFileCompleted( $fileTitle, $isCompleted );

				$requestParams = $this->getRequest()->getParams();
				$connectorClassName = $requestParams['vcType'];
				if ( !empty( $connectorClassName ) && class_exists( $connectorClassName ) ) {
					$connector = new $connectorClassName(); /* @var $connector SDSFormMapping */

					$pandoraObject = $connector->newPandoraSDSObjectFromFormData( $requestParams );
					$json = PandoraJsonLD::toJsonLD( $pandoraObject );

					$pandoraApi = new PandoraAPIClient('http://dev-adam:9292','/api/v0.1/');
					$urlForCollection = $pandoraApi->getCollectionUrl('video151');
					$result = $pandoraApi->createObject( $urlForCollection, $json );
					if ( !$result->isOK() ) {
						print_r( $result );
						$this->setVal( 'errorMessage', $result->getMessage() );
					} else {
						//TODO: redirect
						$this->setVal( 'success', true );
					}
				}
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
