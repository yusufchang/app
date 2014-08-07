<?php

/**
 * VideoAnnotation
 *
 * @author Saipetch Kongkatong
 * @author Liz Lee
 *
 * @date 2014-08-07
 */

$wgExtensionCredits['videoannotation'][] = array(
	'name' => 'VideoAnnotation',
	'author' => array(
		"Saipetch Kongkatong <saipetch at wikia-inc.com>",
		"Liz Lee <liz at wikia-inc.com>",
	),
	'descriptionmsg' => 'wikia-videoannotation-desc',
);

$dir = dirname( __FILE__ ) . '/';

// classes
$wgAutoloadClasses['VideoAnnotationHelper'] =  $dir. 'VideoAnnotationHelper.class.php' ;

// controllers
$wgAutoloadClasses['VideoAnnotationController'] =  $dir . 'VideoAnnotationController.class.php';

// i18n mapping
$wgExtensionMessagesFiles['VideoAnnotation'] = $dir . 'VideoAnnotation.i18n.php';
