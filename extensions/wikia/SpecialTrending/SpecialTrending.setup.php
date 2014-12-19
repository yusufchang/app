<?php
/**
 * CSS Editor
 *
 * @author Damian Jóźwiak
 * @author Bartosz V. Bentkowski
 */
$wgExtensionCredits['specialpage'][] = [
	'name' => 'Trending Pages',
	'description' => 'Admin tool for Trending Pages',
	'authors' => [
		'Damian Jóźwiak',
		'Bartosz V. Bentkowski',
	],
	'version' => 1.0
];

// models
$wgAutoloadClasses['SpecialTrendingHooks'] =  __DIR__ . '/SpecialTrendingHooks.class.php';
$wgAutoloadClasses['SpecialTrendingController'] =  __DIR__ . '/SpecialTrendingController.class.php';

// special page
$wgSpecialPages['Trending'] = 'SpecialTrendingController';
$wgSpecialPageGroups['Trending'] = 'wikia';

// message files
$wgExtensionMessagesFiles['SpecialTrending'] = __DIR__ . '/SpecialTrending.i18n.php';
JSMessages::registerPackage( 'SpecialTrending', ['special-trending-*'] );
