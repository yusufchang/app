<?php

class XboxOneSnapHooks {

	public static function onOasisSkinAssetGroupsBlocking( &$jsAssetGroups ) {
		$jsAssetGroups[] = 'xbox_one_snap_js';

		return true;
	}
}
