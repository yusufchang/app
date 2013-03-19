<?php
/**
 * @author ADi
 * @author Jacek Jursza
 */

class SDSVideoMetadataController extends WikiaSpecialPageController {

	// Flash player width
	const VIDEO_WIDTH = 500;

	public function __construct() {
		parent::__construct('VMD');
	}

	public function indexTest() {

		$file = $this->getVal('video');

		$formBuilder = new PandoraForms( $file );
		$this->setVal( 'form', $formBuilder );

		if($this->request->wasPosted()) {

			$requestParams = $this->getRequest()->getParams();
			$formBuilder->save( $requestParams );

		}

	}

	public function index() {

		$this->response->addAsset('extensions/wikia/SDSVideoMetadata/css/VideoMetadata.scss');
		$this->response->addAsset('extensions/wikia/SDSVideoMetadata/js/VideoMetadata.js');
		$file = $this->getVal('video');

		$fileTitle = Title::newFromText( $file );
		$fileObject = wfFindFile( $fileTitle );
		$fileId = Pandora::pandoraIdFromShortId( $fileTitle->getArticleID() );

		if ( empty( $fileObject ) || !WikiaFileHelper::isFileTypeVideo( $fileObject ) ) {
			$this->setVal( 'isCorrectFile', false );
			return false;
		} else {

			$videoEmbedCode = $fileObject->getEmbedCode( self::VIDEO_WIDTH );
			$this->setVal( 'embedCode', $videoEmbedCode );

			var_dump( VideoClipGamingVideo::getConfig() );
			die;

			$orm = PandoraORM::buildFromId( $fileId );
			if ( $orm->exist ) {
				print_r( '<pre>' );
				print_r( $orm );
				$config = $orm->getConfig();
				foreach ( $config as $key => $params ) {
					$value = $orm->get( $key );
					if ( $value === null ) {
						//skip if no value
						continue;
					}
					if ( $value instanceof PandoraORM ) {

					} else {
						$mapper[ $key ] = $value;
					}
					print_r( $params );
					print_r( $key );
					var_dump( $orm->get( $key ) );
				}
				print_r( $mapper );
				die;
//				$pandoraData = PandoraJsonLD::pandoraSDSObjectFromJsonLD( $obj->response, $fileTitle->getArticleID() );
//				$mapper = SDSFormMapping::newFormDataFromPandoraSDSObject( $pandoraData );
//				$this->setVal( 'vcObj', $mapper );
			}

			if($this->request->wasPosted()) {
				$this->setVal( 'wasPasted', true );
				$isCompleted = (bool) $this->request->getVal('vcCompleted', false);
				$this->setFileCompleted( $fileTitle, $isCompleted );

				$requestParams = $this->getRequest()->getParams();

//				if (

				$connectorClassName = $requestParams['vcType'];
				if ( !empty( $connectorClassName ) && class_exists( $connectorClassName ) ) {
//					$connector = new $connectorClassName(); /* @var $connector SDSFormMapping */

//					$connector->setContextValues( array( 'contentURL' => urlencode( $fileTitle->getFullUrl() ) ) );

					print_r( '<pre>' );
//					$requestParams['about_name'][0] = array( 'name' => 'Planetside 2', 'id' => 'http://sds.wikia.com/sds/~planetside2' );
//					$requestParams['about_name'][1] = array( 'name' => 'Planetside 3', 'id' => 'http://sds.wikia.com/sds/~planetside3' );
//					$requestParams['about_name'][2] = array( 'name' => 'Planetside 4' );
//					print_r( $requestParams );
//					$id = $pandoraApi->getObjectUrl( 129740 );
//
					$id = Pandora::pandoraIdFromShortId( $fileTitle->getArticleID() );
					$orm = new VideoClipGamingVideo( $id );

					$orm->load();

					print_r( $orm );
					$about = $orm->get( 'about_name' );
					print_r( $about );

					die;
					foreach ( $requestParams as $key => $value ) {
						if ( is_array( $value ) ) {
							//if multiple values
							foreach ( $value as $val ) {
								$res = $orm->set( $key, $val );
							}
						} else {
							$res = $orm->set( $key, $value );
						}
						var_dump( $res );
					}

					$orm->set( 'videoObject_name', $fileTitle->getBaseText() );
					print_r( $orm );
					$result = $orm->save();
					var_dump( $result );

					die();
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
