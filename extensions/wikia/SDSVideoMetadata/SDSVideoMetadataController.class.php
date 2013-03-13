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

			$pandoraApi = new PandoraAPIClient();
			$objectUrl = $pandoraApi->getObjectUrl( $fileTitle->getArticleID() );
			$obj = $pandoraApi->getObject( $objectUrl );
			$objExisted = false;

			if ( $obj->isOK() ) {
				$objExisted = true;
				$pandoraData = PandoraJsonLD::pandoraSDSObjectFromJsonLD( $obj->response, $fileTitle->getArticleID() );
				$mapper = SDSFormMapping::newFormDataFromPandoraSDSObject( $pandoraData );
				$this->setVal( 'vcObj', $mapper );
			}

			if($this->request->wasPosted()) {
				$this->setVal( 'wasPasted', true );
				$isCompleted = (bool) $this->request->getVal('vcCompleted', false);
				$this->setFileCompleted( $fileTitle, $isCompleted );

				$requestParams = $this->getRequest()->getParams();
				$connectorClassName = $requestParams['vcType'];
				if ( !empty( $connectorClassName ) && class_exists( $connectorClassName ) ) {
					$connector = new $connectorClassName(); /* @var $connector SDSFormMapping */

					$connector->setContextValues( array( 'contentURL' => urlencode( $fileTitle->getFullUrl() ) ) );

					$pandoraObject = $connector->newPandoraSDSObjectFromFormData( $requestParams, 'main', $objectsList );
					$json = PandoraJsonLD::toJsonLD( $pandoraObject );

					if ( $objExisted ) {
						$result = $pandoraApi->saveObject( $objectUrl, $json );
					} else {
						$urlForCollection = $pandoraApi->getCollectionUrl();
						$result = $pandoraApi->createObject( $urlForCollection, $json );
					}

					if ( !$result->isOK() ) {
						$this->setVal( 'errorMessage', $result->getMessage() );
					} else {
						//TODO: redirect
						$specialPageUrl = SpecialPage::getTitleFor( 'VMD' )->getFullUrl() . '?video='.urlencode( $fileTitle->getPrefixedDBkey() );
						$this->wg->out->redirect( $specialPageUrl );
//						$this->setVal( 'success', true );
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
