<?php
/**
 * Created by adam
 * Date: 12.11.13
 */

class InfoboxApiController extends WikiaApiController {

	protected $service;

	public function getKeys() {
		$title = $this->request->getVal( 'title' );
		$items = [];
		if ( !empty( $title ) ) {
			$items = $this->getService()->getValuesForTitle( $title );
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
