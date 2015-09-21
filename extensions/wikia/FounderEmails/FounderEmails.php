<?php
/**
 * Founder Emails Extensions - helps informing founders about changes on their wiki
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia-inc.com>
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die();
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Founder Emails',
	'author' => 'Adrian \'ADi\' Wieczorek',
	'url' => 'https://github.com/Wikia/app/tree/dev/extensions/wikia/FounderEmails',
	'descriptionmsg' => 'founderemails-desc'
);

/** Messages */

$wgExtensionMessagesFiles['FounderEmails'] = dirname( __FILE__ ) . '/FounderEmails.i18n.php';

/** Extension config */

// These keys allow the FounderEmailEvent::newFromType factory method to create new
// instances based on one of the short keys listed here.
$wgFounderEmailsTypes = [
	'edit' => FounderEmailsEditEvent::class,
	'register' => FounderEmailsRegisterEvent::class,
	'daysPassed' => FounderEmailsDaysPassedEvent::class,
	'completeDigest' => FounderEmailsCompleteDigestEvent::class,
	'viewsDigest' => FounderEmailsViewsDigestEvent::class,
];

$dir = dirname( __FILE__ ) . '/';

/** Autoload classdes */

$wgAutoloadClasses['FounderEmails'] = $dir . 'FounderEmails.class.php';
$wgAutoloadClasses['FounderEmailsEvent'] = $dir . 'FounderEmailsEvent.class.php';

$wgAutoloadClasses['FounderEmailsEditEvent'] = $dir . 'events/FounderEmailsEditEvent.class.php';
$wgAutoloadClasses['FounderEmailsRegisterEvent'] = $dir . 'events/FounderEmailsRegisterEvent.class.php';
$wgAutoloadClasses['FounderEmailsDaysPassedEvent'] = $dir . 'events/FounderEmailsDaysPassedEvent.class.php';
$wgAutoloadClasses['FounderEmailsCompleteDigestEvent'] = $dir . 'events/FounderEmailsCompleteDigestEvent.class.php';
$wgAutoloadClasses['FounderEmailsViewsDigestEvent'] = $dir . 'events/FounderEmailsViewsDigestEvent.class.php';

$wgAutoloadClasses['FounderEmailsController'] = $dir . 'FounderEmailsController.class.php';
$wgAutoloadClasses['SpecialFounderEmails'] = $dir . 'SpecialFounderEmails.class.php';

/** Hooks */

$wgHooks['RecentChange_save'][] = 'FounderEmailsEditEvent::register';
$wgHooks['AddNewAccount'][] = 'FounderEmailsRegisterEvent::register';
$wgHooks['CreateWikiLocalJob-complete'][] = 'FounderEmailsDaysPassedEvent::register';

$wgHooks['GetPreferences'][] = 'FounderEmails::onGetPreferences';
$wgHooks['UserRights'][] = 'FounderEmails::onUserRightsChange';

/** Options */

global $wgCityId;

// Set default for the toggle (applied to all new user accounts).  This is safe even if this user isn't a founder yet.
$wgDefaultUserOptions["founderemails-joins-$wgCityId"] = 0;
$wgDefaultUserOptions["founderemails-edits-$wgCityId"] = 0;
$wgDefaultUserOptions["founderemails-views-digest-$wgCityId"] = 0;
$wgDefaultUserOptions["founderemails-complete-digest-$wgCityId"] = 0;

/** Special Pages */

$wgSpecialPages['FounderEmails'] = 'SpecialFounderEmails';
