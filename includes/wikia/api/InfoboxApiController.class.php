<?php
/**
 * Created by adam
 * Date: 12.11.13
 */

class InfoboxApiController extends WikiaApiController {

	protected $service;

	public function getKeys() {
		$title = $this->request->getVal( 'title' );
		$wid = $this->request->getVal( 'wid', 0 );
		if ( !empty( $title ) ) {
			$items = $this->getService()->getValuesForTitle( $title, $wid );
		} else {
			$items = $this->getService()->getKeys( $wid );
		}
		$this->setVal( 'items', $items );
	}

	public function getValues() {
		$key = $this->request->getVal( 'key' );
		if( !empty( $key ) ) {
			$items = $this->getService()->getValuesForKey( $key );
		}
		$this->setVal( 'items', $items );
	}

	protected function getService() {
		if ( !isset( $this->service ) ) {
			$this->service = new InfoboxService();
		}
		return $this->service;
	}

}
