<?php

/**
 * VideoEditor
 * @author Garth Webb, Hyun Lim, Liz Lee, Saipetch Kongkatong
 */
class VideoEditorSpecialController extends WikiaSpecialPageController {

	public function __construct() {
		parent::__construct( 'VideoEditor', '', false );
	}

	public function init() {
		$this->response->addAsset('video_editor_js');
		$this->response->addAsset('video_editor_css');
		$this->wg->out->addStyle('http://vjs.zencdn.net/c/video-js.css');
	}

	/**
	 * Videos Editor page
	 * @responseParam string url
	 */
	public function index() {
		if ( !$this->wg->User->isLoggedIn() || !$this->wg->User->isAllowed('videoeditor') ) {
			$this->displayRestrictionError();
			return false;
		}

		$this->wg->SupressPageSubtitle = true;

		$this->getContext()->getOutput()->setPageTitle( $this->wf->Msg('videoeditor-page-title') );
		$this->getContext()->getOutput()->setHTMLTitle( $this->wf->Msg('videoeditor-html-title') );

		$this->url = '';
	}

	/**
	 * save video
	 * @requestParam string title
	 * @requestParam integer starttime
	 * @requestParam integer endtime
	 * @responseParam string result [ok/error]
	 * @responseParam string msg
	 */
	public function saveVideo() {
		if ( !$this->wg->User->isLoggedIn() || !$this->wg->User->isAllowed('videoeditor') ) {
			$this->displayRestrictionError();
			return false;
		}

		$videoTitle = $this->request->getVal( 'title', '' );
		if ( empty($videoTitle) ) {
			$this->result = 'error';
			$this->msg = $this->wf->Message( 'videoeditor-error-empty-title' );
			return false;
		}

		$starttime = $this->request->getInt( 'starttime', 0 );
		$endtime = $this->request->getInt( 'endtime', 0 );
		if ( empty($endtime) ) {
			$this->result = 'error';
			$this->msg = $this->wf->Message( 'videoeditor-error-invalid-time' );
			return false;
		}

		$title = Title::newFromText( $videoTitle, NS_FILE );
		if ( !empty($title) ) {
			$file = $this->wf->FindFile( $title );
			if ( !empty($file) ) {
				$this->result = 'ok';
				$this->msg = $this->wf->Message( 'videoeditor-success' );
			}
		}
	}

}
