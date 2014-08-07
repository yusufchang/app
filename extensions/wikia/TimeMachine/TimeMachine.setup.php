<?php

/**
 * TimeMachine
 *
 * @author Christian Williams, Matt K, Danny Chung
 */

$wgExtensionCredits['specialpage'][] = array(
	'name'   => 'TimeMachine',
	'author' => []
);

$dir = dirname(__FILE__) . '/';

// VideoPageTool shared classes
$wgAutoloadClasses['TimeMachine']       =  $dir . 'TimeMachine.class.php';
$wgAutoloadClasses['TimeMachineController']         =  $dir . 'TimeMachineController.class.php';

// i18n mapping
$wgExtensionMessagesFiles['TimeMachine'] = $dir.'TimeMachine.i18n.php';

// special pages
$wgSpecialPages['VideoPageAdmin'] = 'VideoPageAdminSpecialController';

// hooks
$wgHooks['ArticleFromTitle'][] = 'VideoPageToolHooks::onArticleFromTitle';
$wgHooks['ArticlePurge'][] = 'VideoPageToolHooks::onArticlePurge';
$wgHooks['CategorySelectSave'][] = 'VideoPageToolHooks::onCategorySelectSave';
$wgHooks['VideoIngestionComplete'][] = 'VideoPageToolHooks::onVideoIngestionComplete';
$wgHooks['FileDeleteComplete'][] = 'VideoPageToolHooks::onFileDeleteComplete';

// permissions
$wgGroupPermissions['*']['videopagetool'] = false;
$wgGroupPermissions['staff']['videopagetool'] = true;
$wgGroupPermissions['sysop']['videopagetool'] = true;
$wgGroupPermissions['helper']['videopagetool'] = true;
$wgGroupPermissions['vstf']['videopagetool'] = true;

// register messages package for JS
JSMessages::registerPackage('VideoPageTool', array(
	'htmlform-required',
	'videopagetool-confirm-clear-title',
	'videopagetool-confirm-clear-message',
	'videopagetool-description-maxlength-error',
	'videopagetool-video-title-default-text',
	'videopagetool-image-title-default-text',
	'videopagetool-formerror-videokey',
	'videopagetool-formerror-altthumb',
	'videopagetool-formerror-category-name',
));
