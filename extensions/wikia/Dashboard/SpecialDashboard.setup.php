<?php
/**
 * @addtogroup SpecialPage
 *
 * @author Diana Falkowska
 * @author Nikodem Hynek
 */

/**
 * implements Special:Dashboard
 * @addtogroup SpecialPage
 */

$wgExtensionCredits['specialpage'][] = array(
	"name" => "Dashboard",
	"descriptionmsg" => "dshboard-desc",
	'authors' => [
		'Diana Falkowska',
		'Nikodem Hynek',
		'Sebastian Marzjan'
	],
	'url' => 'https://github.com/Wikia/app/tree/dev/extensions/wikia/Dashboard'
);

$dir = dirname(__FILE__) . '/';

$wgAutoloadClasses['DashboardController'] =  $dir . 'DashboardController.class.php';
$wgAutoloadClasses['DashboardSpecialPageController'] =  $dir . 'DashboardSpecialPageController.class.php';
$wgAutoloadClasses['DashboardController'] =  $dir . 'DashboardController.class.php';
$wgAutoloadClasses['Wikia\Dashboard\Components\PortabilityGauge'] =  $dir . 'components/PortabilityGauge.php';

$wgExtensionMessagesFiles['SpecialDashboard'] = $dir.'SpecialDashboard.i18n.php';

$wgSpecialPages[ 'Dashboard'] = 'DashboardSpecialPageController';
$wgSpecialPageGroups['Dashboard'] = 'wikia';
