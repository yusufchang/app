<?php

class UltimateNavigationRegistry {

	static $items = null;

	protected function collect() {
		$items = array();
		wfRunHooks('UltimateNavigationCollect',array(&$items));
		self::$items = $items;
	}

	public function getAll() {
		if ( self::$items === null ) {
			$this->collect();
		}
		return self::$items;
	}

}
