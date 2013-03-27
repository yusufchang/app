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

		if ( empty( $fileObject ) || !WikiaFileHelper::isFileTypeVideo( $fileObject ) ) {
			$this->setVal( 'isCorrectFile', false );
			return false;
		} else {

			$pandoraVideoId = Pandora::pandoraIdFromArticleId( $fileTitle->getArticleID() );

			$videoEmbedCode = $fileObject->getEmbedCode( self::VIDEO_WIDTH );
			$this->setVal( 'embedCode', $videoEmbedCode );

			$orm = PandoraORM::buildFromField( $pandoraVideoId, 'schema:additionalType' );
			if ( $orm->exist ) {
				$config = $orm->getConfig();
				foreach ( $config as $key => $params ) {
					$loadedValue = $orm->get( $key );
					if ( $loadedValue === null ) {
						//skip if no value
						continue;
					}
					if ( is_array( $loadedValue ) ) {
						foreach ( $loadedValue as $val ) {
							if ( $val instanceof PandoraORM ) {
								$value = array( 'name' => $val->get( 'name' ), 'id' => $val->getId() );
							} else {
								$value = $val;
							}
							if ( $params[ 'type' ] === PandoraSDSObject::TYPE_COLLECTION ) {
								$mapper[ $key ][] = $value;
							} else {
								$mapper[ $key ] = $value;
							}
						}
					} else {
						if ( $loadedValue instanceof PandoraORM ) {
							$value = array( 'name' => $loadedValue->get( 'name' ), 'id' => $loadedValue->getId() );
						} else {
							$value = $loadedValue;
						}
						if ( $params[ 'type' ] === PandoraSDSObject::TYPE_COLLECTION ) {
							$mapper[ $key ][] = $value;
						} else {
							$mapper[ $key ] = $value;
						}
					}
				}
				$mapper[ 'vcType' ] = get_class( $orm );
				$this->setVal( 'vcObj', $mapper );
			}

			if($this->request->wasPosted()) {
				$this->setVal( 'wasPasted', true );
				$isCompleted = (bool) $this->request->getVal('vcCompleted', false);
				$this->setFileCompleted( $fileTitle, $isCompleted );

				$requestParams = $this->getRequest()->getParams();

				$connectorClassName = $requestParams['vcType'];

				$orm = PandoraORM::buildFromType( $connectorClassName, $pandoraVideoId );
				foreach ( $orm->getConfig() as $key => $params ) {
					//TODO: delete this hack, after format changed
//					if ( isset( $params[ 'childType' ] ) ) {
//						foreach ( $requestParams[ $key ] as $data ) {
//							$changedParams[] = array( 'name' => $data );
//						}
//						if ( isset( $params[ 'value' ] ) ) {
//							$orm->set( $key, $params[ 'value' ] );
//						}
					if ( isset( $params[ 'childType' ] ) ) {
						$requestParams[ $key ] = array( array( 'name' => $requestParams[ $key ] ) );
					}
					if ( isset( $requestParams[ $key ] ) ) {
						if ( is_array( $requestParams[ $key ] ) ) {
							foreach ( $requestParams[ $key ] as $values ) {
								$orm->set( $key, $values );
							}
						} else {
							$orm->set( $key, $requestParams[ $key ] );
						}
					}
				}
				//add name as video object name
				$orm->set( 'name', $fileTitle->getBaseText() );
				$orm->set( 'content_url', $fileTitle->getFullUrl() );
				//use default
				$orm->set( 'additional_type', null );
				$result = $orm->save();

				if ( !$result->isOK() ) {
					$this->setVal( 'errorMessage', $result->getMessage() );
				} else {
					//TODO: redirect
//					$specialPageUrl = SpecialPage::getTitleFor( 'VMD' )->getFullUrl() . '?video='.urlencode( $fileTitle->getPrefixedDBkey() );
//					$this->wg->out->redirect( $specialPageUrl );
//						$this->setVal( 'success', true );
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

	public function referenceItem() {
		$item = $this->getVal('item');
		$pos = $this->getVal('pos');
		$propName = $this->getVal('propName');
		$removeBtnMsg = $this->getVal('removeBtnMsg');

		$this->objectName = htmlspecialchars($item['name']);
		$this->objectId = htmlspecialchars($item['id']);
		$this->objectParam = 'Additional info';
		$this->imgURL = '#';
		$this->removeMsg = $removeBtnMsg;
		$this->pos = $pos;
		$this->propName = $propName;


		$this->response->setTemplateEngine(WikiaResponse::TEMPLATE_ENGINE_MUSTACHE);
	}

}
