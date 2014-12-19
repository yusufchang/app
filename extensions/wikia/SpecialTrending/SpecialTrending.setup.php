<?php
/**
 * CSS Editor
 *
 * @author Bartosz V. Bentkowski
 */
$dir = dirname(__FILE__) . '/';

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Trending Pages',
	'description' => 'Admin tool for Trending Pages',
	'authors' => array(
		'Bartosz V. Bentkowski',
	),
	'version' => 1.0
);

// models
$wgAutoloadClasses['SpecialTrendingHooks'] =  $dir . 'SpecialTrendingHooks.class.php';

// special page
$wgSpecialPages['Trending'] = 'SpecialTrendingController';
$wgSpecialPageGroups['Trending'] = 'wikia';

// message files
$wgExtensionMessagesFiles['SpecialTrending'] = $dir.'SpecialTrending.i18n.php';
JSMessages::registerPackage( 'SpecialTrending', array( 'special-trending-*' ) );
