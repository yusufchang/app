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
		'Nikodem Hynek'
	],
	'url' => 'https://github.com/Wikia/app/tree/dev/extensions/wikia/Dashboard'
);

$dir = dirname(__FILE__) . '/';

//$wgAutoloadClasses['DashboardQueryPage']  = $dir . 'SpecialDashboard_body.php';
$wgAutoloadClasses['DashboardController'] =  $dir . 'DashboardController.class.php';
$wgAutoloadClasses['DashboardSpecialPageController'] =  $dir . 'DashboardSpecialPageController.class.php';
$wgAutoloadClasses['DashboardController'] =  $dir . 'DashboardController.class.php';
$wgAutoloadClasses['Wikia\Dashboard\Components\PortabilityGauge'] =  $dir . 'components/PortabilityGauge.php';

//$wgSpecialPages[ 'Dashboard' ] = 'DashboardQueryPage';
$wgSpecialPages[ 'Dashboard'] = 'DashboardSpecialPageController';
//$wgSpecialPages[ 'Dashboard'] = 'DashboardController';
$wgSpecialPageGroups['Dashboard'] = 'wikia';


//$wgGroupPermissions['*']['dashboard'] = true;
