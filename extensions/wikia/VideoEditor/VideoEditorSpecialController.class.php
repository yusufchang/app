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
