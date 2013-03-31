<?php
/**
 * @author ADi
 * @author Jacek Jursza
 */

class SDSVideoMetadataController extends WikiaSpecialPageController {

	// Flash player width
	const VIDEO_WIDTH = 500;

	//Forms config
	protected $formsConfig = array(
		'videoObject_description' => array(
			'controller' => 'PandoraForms',
			'template' => 'default',
			'textarea' => true,
			'label' => 'sdsvideometadata-vc-description',
			'ormKey' => 'description'
		),
		'videoObject_inLanguage' => array(
			'controller' => 'PandoraForms',
			'template' => 'default',
			'label' => 'sdsvideometadata-vc-language',
			'ormKey' => 'inLanguage'
		),
		'videoObject_subTitleLanguage' => array(
			'controller' => 'PandoraForms',
			'template' => 'default',
			'label' => 'sdsvideometadata-vc-subtitles',
			'ormKey' => 'subTitleLanguage'
		),
//		'recipe_name' => array (
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'label' => 'sdsvideometadata-vc-recipe',
//			'ormKey' => ''
//		),
//		'provider_name' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo VideoClipHowToVideo',
//			'label' => 'sdsvideometadata-vc-distributor',
//			'ormKey' => ''
//		),
//		'publisher_name' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo VideoClipHowToVideo',
//			'label' => 'sdsvideometadata-vc-publisher',
//			'ormKey' => ''
//		),
//		'track_name' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipMusicVideo',
//			'label' => 'sdsvideometadata-vc-song',
//			'ormKey' => ''
//		),
//		'musicGroup_name' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipMusicVideo',
//			'label' => 'sdsvideometadata-vc-artist',
//			'ormKey' => ''
//		),
//		'musicRecording_musicLabel' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipMusicVideo',
//			'label' => 'sdsvideometadata-vc-music-label',
//			'ormKey' => ''
//		),
//		'videoObject_genre' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'literal_list',
//			'type' => 'VideoClipTravelVideo VideoClipMusicVideo VideoClipCookingVideo
//						VideoClipCraftVideo VideoClipHowToVideo',
//			'label' => 'sdsvideometadata-vc-genre',
//			'ormKey' => ''
//		),
//		'about_location' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipTravelVideo',
//			'label' => 'sdsvideometadata-vc-location',
//			'ormKey' => ''
//		),
		'about_name' => array(
			'controller' => 'PandoraForms',
			'template' => 'reference_list',
			'type' => 'VideoClipGamingVideo',
			'label' => 'sdsvideometadata-vc-game',
			'ormKey' => 'about_name'
		),
//		'series_name' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipTVVideo',
//			'label' => 'sdsvideometadata-vc-series',
//			'ormKey' => ''
//		),
//		'season_name' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipTVVideo',
//			'label' => 'sdsvideometadata-vc-season',
//			'ormKey' => ''
//		),
//		'movie_name' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'reference_list',
//			'type' => 'VideoClipMovieTrailersVideo',
//			'label' => 'sdsvideometadata-vc-movie',
//			'ormKey' => ''
//		),
//		'videoObject_rating' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'default',
//			'type' => 'VideoClipMovieTrailersVideo',
//			'label' => 'sdsvideometadata-vc-trailer-rating',
//			'ormKey' => ''
//		),
		'videoObject_keywords' => array(
			'controller' => 'PandoraForms',
			'template' => 'literal_list',
			'type' => 'VideoClipGamingVideo VideoClipTVVideo',
			'label' => 'sdsvideometadata-vc-kind',
			'ormKey' => 'keywords'
		),
		'videoObject_isFamilyFriendly' => array(
			'controller' => 'PandoraForms',
			'template' => 'select',
			'type' => 'VideoClipGamingVideo VideoClipMovieTrailersVideo',
			'label' => 'sdsvideometadata-vc-age-gate',
			'options' => array(
				array(
					'value' => '',
					'text' => 'sdsvideometadata-vc-boolean-not-set'
				),
				array(
					'value' => 'true',
					'text' => 'sdsvideometadata-vc-boolean-true'
				),
				array(
					'value' => 'false',
					'text' => 'sdsvideometadata-vc-boolean-false'
				)
			),
			'ormKey' => 'isFamilyFriendly'
		),
//		'videoObject_contentFormat' => array(
//			'controller' => 'PandoraForms',
//			'template' => 'select',
//			'type' => 'VideoClipMusicVideo',
//			'label' => 'sdsvideometadata-vc-pal',
//			'options' => array(
//				array(
//					'value' => '',
//					'text' => 'sdsvideometadata-vc-boolean-not-set'
//				),
//				array(
//					'value' => 'PAL',
//					'text' => 'sdsvideometadata-vc-boolean-true'
//				)
//			),
//			'ormKey' => 'isFamilyFriendly'
//		),
		'videoObject_associatedMedia' => array(
			'controller' => 'PandoraForms',
			'template' => 'reference_list',
			'type' => 'VideoClipGamingVideo',
			'label' => 'sdsvideometadata-vc-soundtrack',
			'ormKey' => 'soundtrack'
		),
		'videoObject_setting' => array(
			'controller' => 'PandoraForms',
			'template' => 'literal_list',
			'type' => 'VideoClipGamingVideo VideoClipMusicVideo VideoClipTVVideo VideoClipMovieTrailersVideo',
			'label' => 'sdsvideometadata-vc-setting',
			'ormKey' => 'setting'
		),
	);

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
		$this->response->addAsset('resources/wikia/libraries/mustache/mustache.js');
		$this->response->addAsset('extensions/wikia/SDSVideoMetadata/js/VideoMetadata.js');
		$file = $this->getVal('video');

		$fileTitle = Title::newFromText( $file );
		$fileObject = wfFindFile( $fileTitle );

		if ( empty( $fileObject ) || !WikiaFileHelper::isFileTypeVideo( $fileObject ) ) {
			$this->setVal( 'isCorrectFile', false );
			return false;
		} else {

			//prepare pandoraForms
			$forms = new PandoraForms();
			$forms->setConfig( $this->formsConfig );

			$pandoraVideoId = Pandora::pandoraIdFromArticleId( $fileTitle->getArticleID() );

			$videoEmbedCode = $fileObject->getEmbedCode( self::VIDEO_WIDTH );
			$this->setVal( 'embedCode', $videoEmbedCode );

			$orm = PandoraORM::buildFromField( $pandoraVideoId, 'schema:additionalType', 'VideoObject' );
			if ( $orm->exist ) {
				$forms->loadFromORM( $orm );
				$mapper[ 'vcType' ] = get_class( $orm );
				$this->setVal( 'vcObj', $mapper );
			}

			if($this->request->wasPosted()) {
				$this->setVal( 'wasPasted', true );
				$isCompleted = (bool) $this->request->getVal('vcCompleted', false);
				$this->setFileCompleted( $fileTitle, $isCompleted );

				$requestParams = $this->getRequest()->getParams();

				$saveOrm = $forms->getOrmFromRequest( $requestParams, $pandoraVideoId );
				$saveOrm->set( 'name', $fileTitle->getBaseText() );
				$saveOrm->set( 'content_url', $fileTitle->getFullUrl() );
				//sets filed with key = 'additional_type' as default (using config 'value' filed)
				$saveOrm->set( 'additional_type' );

				$result = $saveOrm->save();

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
			$this->setVal( 'formBuilder', $forms );
		}

		$this->setVal('file', $fileTitle->getBaseText());
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
		$this->imgURL = '';
		$this->removeMsg = $removeBtnMsg;
		$this->pos = $pos;
		$this->propName = $propName;


		$this->response->setTemplateEngine(WikiaResponse::TEMPLATE_ENGINE_MUSTACHE);
	}

}
