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
		$file = $this->getVal('video');


		$fileTitle = Title::newFromText( $file );
		$fileObject = wfFindFile( $fileTitle );
		if ( empty( $fileObject ) ) {
			$this->setVal( 'isCorrectFile', false );
			return false;
		} else {
			$this->setVal( 'isCorrectFile', true );
		}



		$this->setVal('file', $file);
	}

}
