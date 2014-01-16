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

		if ( !empty( $_POST ) ) {
			$this->saveType( $_POST );
		}

		//get next key and data
		$current = $this->getBatch($k);

		$this->setVal( 'data', $current );
		$this->setVal( 'k', $k );
	}

	protected function saveType( $values ) {
		$db = wfGetDB(DB_MASTER, array(), F::app()->wg->ExternalDatawareDB);
		$num = ( count( $values ) - 1 ) / 3;
		//prepare data
		for ( $i = 0; $i < $num; $i++ ) {
			if ( !empty( $values[ 'type_'.$i ] ) ) {
				(new WikiaSQL())->UPDATE( 'info_schema_map' )
					->SET( 'type', $values[ 'type_'.$i ] )
					->WHERE( 'info_key' )->EQUAL_TO( $values[ 'key_'.$i ] )
					->AND_( 'template' )->EQUAL_TO( $values[ 'template_'.$i ] )
				->run( $db, function(){});
			}
		}
	}

	protected function getBatch( $k ) {
		$all = $this->service->getSchemaKeys();
		$batches = array_chunk( $all, 25 );
		return $batches[$k];
	}

}
