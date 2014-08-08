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
$wgAutoloadClasses['TimeMachine']           =  $dir . 'TimeMachine.class.php';
$wgAutoloadClasses['TimeMachineController'] =  $dir . 'TimeMachineController.class.php';
$wgAutoloadClasses['TimeMachineHooks']      =  $dir . 'TimeMachineHooks.class.php';

// hooks
$app->registerHook('OutputPageBeforeHTML', 'TimeMachineHooks', 'onOutputPageBeforeHTML');

// i18n mapping
$wgExtensionMessagesFiles['TimeMachine'] = $dir.'TimeMachine.i18n.php';


// register messages package for JS
JSMessages::registerPackage( 'TimeMachine', [] );
