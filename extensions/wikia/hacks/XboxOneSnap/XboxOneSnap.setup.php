<?php

$wgExtensionCredits['specialpage'][] = [
	'path' => __DIR__,
	'name' => 'Xbox One Snap',
	'author' => 'Warkot',
	'descriptionmsg' => 'xbox-one-snap-desc'
];

$wgExtensionMessagesFiles['XboxOneSnap'] = __DIR__ . '/XboxOneSnap.i18n.php';

$wgAutoloadClasses['XboxOneSnapHooks'] = __DIR__ . '/XboxOneSnapHooks.class.php';

$wgHooks['OasisSkinAssetGroupsBlocking'][] = 'XboxOneSnapHooks::onOasisSkinAssetGroupsBlocking';
