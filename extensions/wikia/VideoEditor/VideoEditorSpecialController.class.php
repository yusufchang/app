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
	 */
	public function index() {
		$this->wg->SupressPageSubtitle = true;

		$this->getContext()->getOutput()->setPageTitle( $this->wf->Msg('videoeditor-page-title') );
		$this->getContext()->getOutput()->setHTMLTitle( $this->wf->Msg('videoeditor-html-title') );


	}

}
