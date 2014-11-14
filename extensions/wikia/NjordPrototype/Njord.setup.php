<?php
/**
 * This extension handles Modular Main Pages
 * prototype
 */
$dir = dirname( __FILE__ );

/**
 * classes
 */

$wgAutoloadClasses['NjordHooks'] =  $dir . '/NjordHooks.class.php';
$wgAutoloadClasses['NjordModel'] =  $dir . '/models/NjordModel.class.php';

$wgAutoloadClasses['WikiDataModel'] =  $dir . '/models/WikiDataModel.class.php';
$wgAutoloadClasses['CharacterModuleModel'] =  $dir . '/models/CharacterModuleModel.class.php';
$wgAutoloadClasses['ContentEntity'] =  $dir . '/models/ContentEntity.class.php';

$wgAutoloadClasses['NjordController'] =  $dir . '/NjordController.class.php';
$wgAutoloadClasses['NjordCharacterController'] =  $dir . '/NjordCharacterController.class.php';
/** Helper controllers */
$wgAutoloadClasses['ImageUploadController'] =  $dir . '/ImageUploadController.class.php';

$wgHooks['ParserFirstCallInit'][] = 'NjordHooks::onParserFirstCallInit';

if ( !empty( $wgEnableNjordExtOnNewWikias ) ) {
	$wgHooks['CreateWikiLocalJob-complete'][] = 'NjordHooks::onCreateNewWikiComplete';
}

$wgAvailableRights[] = 'njordeditmode';

$wgGroupPermissions['*']['njordeditmode'] = false;
$wgGroupPermissions['staff']['njordeditmode'] = true;
$wgGroupPermissions['sysop']['njordeditmode'] = true;
$wgGroupPermissions['bureaucrat']['njordeditmode'] = true;
$wgGroupPermissions['helper']['njordeditmode'] = true;

NjordHooks::$templateDir = $dir . '/templates';
