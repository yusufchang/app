<?php

/**
 * Ultimate navigation extension
 * @package MediaWiki
 *
 * @author wladek
 */
$wgExtensionCredits['other'][] = array(
	"name" => "Ultimate Navigation",
	"description" => "Interconnects various navigation actions based on current context",
	"author" => array( 'Władysław Bodzek' )
);


$wgAutoloadClasses['UltimateNavigationRegistry'] = __DIR__ . '/UltimateNavigationRegistry.class.php';
$wgAutoloadClasses['UltimateNavigationHooks'] = __DIR__ . '/UltimateNavigationHooks.class.php';
$wgAutoloadClasses['UltimateNavigationController'] = __DIR__ . '/UltimateNavigationController.class.php';
$wgAutoloadClasses['UltimateNavigationHelper'] = __DIR__ . '/UltimateNavigationHelper.class.php';

$wgHooks['OasisSkinAssetGroups'][] = 'UltimateNavigationHooks::onOasisSkinAssetGroups';
$wgHooks['UltimateNavigationCollect'][] = 'UltimateNavigationHooks::onUltimateNavigationCollect';
$wgHooks['BeforePageDisplay'][] = 'UltimateNavigationHooks::onBeforePageDisplay';
