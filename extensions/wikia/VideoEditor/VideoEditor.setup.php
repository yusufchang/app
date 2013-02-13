<?php
/**
 * VideoEditor
 * @author Garth Webb, Hyun Lim, Liz Lee, Saipetch Kongkatong
 */

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'VideoEditor',
	'author' => array( 'Garth Webb', 'Hyun Lim', 'Liz Lee', 'Saipetch Kongkatong' )
);

$dir = dirname(__FILE__) . '/';
$app = F::app();

//classes
$app->registerClass( 'VideoEditorSpecialController', $dir.'VideoEditorSpecialController.class.php' );

// i18n mapping
$app->registerExtensionMessageFile( 'VideoEditor', $dir.'VideoEditor.i18n.php' );

// special pages
$app->registerSpecialPage( 'VideoEditor', 'VideoEditorSpecialController' );

// rights
$wgAvailableRights[] = 'videoeditor';
$wgGroupPermissions['*']['videoeditor'] = false;
$wgGroupPermissions['staff']['videoeditor'] = true;
