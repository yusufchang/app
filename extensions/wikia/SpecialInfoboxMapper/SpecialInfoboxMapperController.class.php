<?php
/**
 * Created by adam
 * Date: 20.12.13
 */
class SpecialInfoboxMapperController extends WikiaSpecialPageController {

	protected $service;

	public function __construct() {
		$this->service = new InfoboxService();
		parent::__construct( 'InfoboxMapper' );
	}

	public function index() {
		$k = isset( $_REQUEST['k'] ) ? $_REQUEST['k'] : 0 ;

		if ( isset( $_POST['type'] ) ) {
			$this->saveType( $_POST );
		}

		//get next key and data
		$current = $this->getList($k);

		$this->setVal( 'data', $current );
		$this->setVal( 'k', ++$k );
	}

	protected function saveType( $values ) {
		var_dump( 'saved' );
		var_dump( $values );
	}

	protected function getList( $k ) {
		$list = $this->service->getKeys();
		$batches = array_chunk( $list, 10 );
		return $batches[$k];
	}

}
