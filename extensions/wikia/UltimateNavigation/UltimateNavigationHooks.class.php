<?php

class UltimateNavigationHooks {

	static public function onOasisSkinAssetGroups( &$assetGroups ) {
		$assetGroups[] = 'ultimate_navigation_js';

		return true;
	}

	static public function onUltimateNavigationCollect( &$items ) {

		return true;
	}

	static public function onBeforePageDisplay( OutputPage $wgOut ) {
		global $wgScriptPath, $wgExtensionsPath;
		$wgOut->addStyle( "$wgScriptPath/extensions/wikia/UltimateNavigation/vendor/qtip2/jquery.qtip.min.css" );
		$wgOut->addStyle( "$wgScriptPath/extensions/wikia/UltimateNavigation/css/ultinav.css" );
		$wgOut->addStyle( AssetsManager::getInstance()->getURL('extensions/wikia/UserProfilePageV3/css/UserProfilePage.scss')[0] );

		return true;
	}

}